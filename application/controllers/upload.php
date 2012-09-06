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
		
		if ( isset($_POST['file_element']) && isset($_POST['event']) && isset($_POST['guest']) && isset($_POST['type']))
		{
			$image = $_POST['file_element'];
			$event = $_POST['event'];
			$guest = $_POST['guest'];
			$type = $_POST['type'];
			
			$filename = $_FILES['mf_file_uploadArea']['name']; // Get the name of the file (including file extension).
			$ext = substr($filename, strpos($filename,'.')+1, strlen($filename)-1); // Get the extension from the filename.
			$tmp_file = $_FILES['mf_file_uploadArea']['tmp_name'];
			
			if(!in_array($ext,$allowedExtensions))
			{
				$these = implode(', ', $allowedExtensions);
				$result = '{"error":"File has an invalid extension, it should be one of '. $these . '."}';
			} else {
				$new_filename = time() . "-" . preg_replace("/[^A-Za-z0-9.]/", "", $filename);
				
				move_uploaded_file($_FILES["mf_file_uploadArea"]["tmp_name"],
  $_SERVER['DOCUMENT_ROOT'] . "/tmp-files/" . $new_filename);
  				
				list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/tmp-files/" . $new_filename);
				
				$result = '{
					"status":200,
					"image":"' . $new_filename . '",
					"width":' . $width . ',
					"height":' . $height . ',
					"type":"' . $type . '"
				}';
			}
			
			echo $result;
		} else {
			echo '{"error":"Something went horribly wrong."}';
		}
	}
	
	public function crop()
	{
		$segments = $this->uri->total_segments();
		
		if ( $segments == 5 && $this->uri->segment(2) == "crop" )
		{
			$image = $this->uri->segment(3);
			$orig_width = $this->uri->segment(4);
			$orig_height = $this->uri->segment(5);
			
			// Calculate aspect ratio
			$maxSize = 700;
	        $wRatio = $maxSize / $orig_width;
	        $hRatio = $maxSize / $orig_height;
	
	        // Calculate a proportional width and height no larger than the max size.
	        if ( ($wRatio * $orig_height) < $maxSize )
	        {
	            // Image is horizontal
	            $tHeight = ceil($wRatio * $orig_height);
	            $tWidth  = $maxSize;
	        }
	        else
	        {
	            // Image is vertical
	            $tWidth  = ceil($hRatio * $orig_width);
	            $tHeight = $maxSize;
	        }
			
			$data = array(
				'image' => $image,
				'orig_width' => $orig_width,
				'orig_height' => $orig_height,
				'width' => $tWidth,
				'height' => $tHeight,
				'wRatio' => $wRatio,
				'hRatio' => $hRatio
			);
			$this->load->view('event/crop', $data);
		} else {
			echo "Something went wrong with the upload.";
		}
	}
	
	public function square()
	{
		if ( IS_AJAX && isset($_POST) )
		{
			/*
			Array
			(
			    [image] => /tmp-files/1345052357-goonies.jpg
			    [x] => 0
			    [y] => 22
			    [w] => 460
			    [h] => 460
			    [new_width] => 460
			    [new_height] => 700
			    [orig_width] => 957
			    [orig_height] => 1458
			    [wRatio] => 0.73145245559
			    [hRatio] => 0.480109739369
			)
			*/
			
			$file = explode("/", $_POST['image']);
			$filename = time() . "-" . end($file);
			
			// GET COORDINATES OF ORIGINAL
			$multiple = $_POST['orig_width'] / $_POST['new_width'];
			$orig_x = round($multiple * $_POST['x']);
			$orig_y = round($multiple * $_POST['y']);
			$orig_w = round($multiple * $_POST['w']);
			$orig_h = round($multiple * $_POST['h']);

			// CROP TO SQUARE			
			$targ_w = $targ_h = $orig_w;
			$jpeg_quality = 90;
		
			$src = $_SERVER['DOCUMENT_ROOT'] . $_POST['image'];
			$img_r = imagecreatefromjpeg($src);
			$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
		
			imagecopyresampled($dst_r, $img_r, 0, 0, $orig_x, $orig_y, $targ_w, $targ_h, $orig_w, $orig_h);
			imagejpeg($dst_r, $_SERVER['DOCUMENT_ROOT'] . "/tmp-files/" . $filename, $jpeg_quality);
						
			// URL on which we have to post data
			
			$url = "http://devapi.snapable.com/private_v1/photo/";
			
			// Data to send
			$post_data = array();
			$post_data['event'] = $_POST['event'];
			$post_data['guest'] = $_POST['guest'];
			$post_data['type'] = $_POST['type'];
			// The Photo
			$post_data['image'] = "@" . $_SERVER['DOCUMENT_ROOT'] . "/tmp-files/" . $filename;
			
			$length = 8;
			$nonce = "";
			while ($length > 0) {
			    $nonce .= dechex(mt_rand(0,15));
			    $length -= 1;
			}
			
			$api_key = 'abc123';
			$api_secret = '123';
			$verb = 'POST';
			$path = '/private_v1/photo/';
			$x_path_nonce = $nonce;
			$x_snap_date = gmdate("Ymd", time()) . 'T' . gmdate("His", time()) . 'Z';
			
			$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
			$signature = hash_hmac('sha1', $raw_signature, $api_secret);
			 
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-type: multipart/form-data',
				'X-SNAP-Date: ' . $x_snap_date ,
				'X-SNAP-nonce: ' . $x_path_nonce ,
				'Authorization: SNAP ' . $api_key . ':' . $signature 
	    	));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			$response = curl_exec($ch);
			unlink($_SERVER['DOCUMENT_ROOT'] . "/tmp-files/" . $filename);
			echo '{"status":200,"result":' . $response . '}';
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
			move_uploaded_file($_FILES["guests-file-input"]["tmp_name"],
  $server_path . $new_filename);  
	       
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