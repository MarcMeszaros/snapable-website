<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {

	function __construct()
	{
    	parent::__construct(); 	    	
	}
	
	public function index()
	{
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array("jpeg","jpg","JPG","JPEG");
		// max file size in bytes
		$sizeLimit = 10 * 1024 * 1024;

		if ( isset($_FILES['file_element']) && isset($_POST['event']) )
		{
			$image = $_FILES['file_element'];
			$event = $_POST['event'];
			$type = (!empty($_POST['type'])) ? $_POST['type'] : '/'.SnapApi::$api_version.'/type/6/';

			$img_type = explode('/', $image['type']);
			$filename = $image['name']; // Get the name of the file (including file extension).
			$tmp_file = $image['tmp_name'];

			// if it's a jpeg continue
			if(isset($img_type[1]) && $img_type[1] == 'jpeg')
			{
				$new_filename = time() . "-" . preg_replace("/[^A-Za-z0-9.]/", "", $filename);
				
				move_uploaded_file($image["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/tmp-files/" . $new_filename);
  				list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/tmp-files/" . $new_filename);
				
				$result = array(
					'status' => 200,
					'image' => $new_filename,
					'width' => $width,
					'height' => $height,
					'type' => $type,
				);
			} else {
				$this->output->set_status_header('400');
				$these = implode(', ', $allowedExtensions);
				$result = array(
					'error' => "File has an invalid extension, it should be one of ". $these,
				);
			}

			echo json_encode($result);
		} else {
			$this->output->set_status_header('400');
			echo json_encode(array("error" => "Something went horribly wrong."));
		}
	}
	
	public function crop($image, $orig_width, $orig_height)
	{		
		// get the temp image details
		list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/tmp-files/" . $image);
		
		// Calculate aspect ratio
		$maxSize = 700;
		$wRatio = $maxSize / $width;
        $hRatio = $maxSize / $height;

        // Calculate a proportional width and height no larger than the max size.
        if ( $wRatio < $hRatio )
        {
            // Image is horizontal
            $tHeight = ceil($wRatio * $height);
            $tWidth  = $maxSize;
        }
        else
        {
            // Image is vertical
            $tWidth  = ceil($hRatio * $width);
            $tHeight = $maxSize;
        }
		
		$data = array(
			'image' => $image,
			'orig_width' => $width,
			'orig_height' => $height,
			'width' => $tWidth,
			'height' => $tHeight,
			'wRatio' => $wRatio,
			'hRatio' => $hRatio
		);
		$this->load->view('event/crop', $data);
	}
	
	public function square()
	{
		if ( IS_AJAX && isset($_POST) )
		{
			try{
				list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/tmp-files/" . $_POST['image']);
				$filename = time() . "-" . $_POST['image'];
				
				// GET COORDINATES OF ORIGINAL
				//$multiple = $width / $_POST['new_width'];
				$orig_x = round($_POST['x']);
				$orig_x2 = round($_POST['x2']);
				$orig_y = round($_POST['y']);
				$orig_y2 = round($_POST['y2']);
				$orig_w = round($_POST['w']);
				$orig_h = round($_POST['h']);

				// CROP TO SQUARE			
				$targ_w = $targ_h = $orig_w;
				$jpeg_quality = 90;

				// get a handle on the non-cropped image
				$src = $_SERVER['DOCUMENT_ROOT'] . "/tmp-files/" . $_POST['image'];
				$img_r = imagecreatefromjpeg($src);
				$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

				// create the cropped image
				imagecopyresampled($dst_r, $img_r, 0, 0, $orig_x, $orig_y, $targ_w, $targ_h, $orig_w, $orig_h);
				imagejpeg($dst_r, $_SERVER['DOCUMENT_ROOT'] . "/tmp-files/" . $filename, $jpeg_quality);

				// delete non-cropped temp image
				unlink($_SERVER['DOCUMENT_ROOT'] . "/tmp-files/" . $_POST['image']);

				// upload the image
				$verb = 'POST';
				$path = '/photo/';
				// Data to send
				$params = array(
					'event' => $_POST['event'],
					// The Photo
					'image' => "@" . $_SERVER['DOCUMENT_ROOT'] . "/tmp-files/" . $filename,
				);
				$params['type'] = (!empty($_POST['type'])) ? $_POST['type'] : '/'.SnapApi::$api_version.'/type/6/';
				if (!empty($_POST['guest'])) {
					$params['guest'] = $_POST['guest'];
				}
				$headers = array(
					'Content-Type' => 'multipart/form-data',
				);
				$resp = SnapApi::send($verb, $path, $params, $headers);

				unlink($_SERVER['DOCUMENT_ROOT'] . "/tmp-files/" . $filename);
				$this->output->set_status_header($resp['code']);
				echo '{"status":200,"result":' . $resp['response'] . '}';
			} catch (Exception $e) {
				// TODO handle error
			}
		} else {
			show_404();
		}
	}
	
	
	function csv()
	{
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array("csv","CSV");
		// max file size in bytes
		$sizeLimit = 10 * 1024 * 1024;
		
		if ( isset($_FILES['guests-file-input']) )
		{
			ini_set('auto_detect_line_endings', true);
			
			$filename = $_FILES['guests-file-input']['name']; // Get the name of the file (including file extension).
			$ext = substr($filename, strpos($filename,'.')+1, strlen($filename)-1); // Get the extension from the filename.
			$tmp_file = $_FILES['guests-file-input']['tmp_name'];
			
			$server_path = $_SERVER['DOCUMENT_ROOT'] . "/tmp-files/";
	        $new_filename = time() . "-" . preg_replace("/[^A-Za-z0-9.]/", "", $filename);
			move_uploaded_file($_FILES["guests-file-input"]["tmp_name"], $server_path . $new_filename);  
	       
	       	$row = 1;
	       	$email_row = 0;
	       	$name_row = 0;
	       	$guest_row = 0;
	       	$rows = "";
	       	$row_data = "";
	       	
			if (($handle = fopen($server_path . $new_filename, "r")) !== FALSE) {
			    
			    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			        $num = count($data);
			        
			        if ( $row <= 5 )
			        {
			        	$row++;
			        	$numnum = 1;
			        	$empty = false;
			        	$row_data .= "{";
			        	
			        	for ($c=0; $c < $num; $c++) {
			        	    if ( $numnum <= 3 && ( strtolower($data[$c]) != "name" && strtolower($data[$c]) != "email" && strtolower($data[$c]) != "type" && strtolower($data[$c]) != "email address" && strtolower($data[$c]) != "guest type" && strtolower($data[$c]) != "guest_type" && strtolower($data[$c]) != "guest-type" ) )
			        	    {
				        	    $label = "unknown";
				        	    switch ($numnum) {
								    case 1:
								        $label = "one";
								        break;
								    case 2:
								        $label = "two";
								        break;
								    case 3:
								        $label = "three";
								        break;
								}
				        	    
				        	    // check if is email address
				        	    if( preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $data[$c]) > 0)
				        	    {
				        	    	// is email
				        	    	$email_row = $label;
				        	    } else {
					        	    // isn't, check if guest
					        	    $types = array("organizer","bride/groom","wedding party","family","guest"); // SWITCH TO FILL ARRAY FROM API AND ENSURE THEY'RE LOWERCASE!!!!
					        	    if ( in_array(strtolower($data[$c]), $types) )
					        	    {
					        	    	// is guest type
						        	    $guest_row = $label;
					        	    } else {
					        	    	// isn't guest or email, must be a name
						        	    $name_row = $label;
					        	    }
				        	    }
				        	    
				        	    $row_data .= '"' . $label . '": "' . $data[$c] . '",';
				        	    
				        	    $numnum++;
				        	} else {
					        	$empty = true;
				        	}
			        	}
			        	$row_data = substr($row_data, 0, -1);
			        	if ( $empty == false )
			        	{
			        		$row_data .= "},";
			        	}
			        }			        
			    }
			    fclose($handle);
			    
			    $rows = substr($row_data, 0, -1);
			    
			    echo '{
			    	"status": 200,
			    	"filename": "' . $new_filename . '",
					"header": {
						"email": "' . $email_row . '",
						"name": "' . $name_row . '",
						"type": "' . $guest_row . '"
					},
					"rows": [' . $rows . ']
			    }';
			}
	        
		} else {
			echo "Big Fat Fail";
		}
	}
}