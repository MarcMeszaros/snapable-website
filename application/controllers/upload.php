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
		// max file size in bytes (10MB)
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
			if(isset($img_type[1]) && $img_type[1] == 'jpeg' && $image['size'] <= $sizeLimit)
			{
				try{
					// upload the image
					$verb = 'POST';
					$path = '/photo/';
					// Data to send
					$params = array(
						'event' => $_POST['event'],
						// The Photo
						'image' => "@" . $tmp_file,
					);
					$params['type'] = (!empty($_POST['type'])) ? $_POST['type'] : '/'.SnapApi::$api_version.'/type/6/';
					if (!empty($_POST['guest'])) {
						$params['guest'] = $_POST['guest'];
					}
					$headers = array(
						'Content-Type' => 'multipart/form-data',
					);
					$resp = SnapApi::send($verb, $path, $params, $headers);

					unlink($tmp_file);
					$this->output->set_status_header($resp['code']);
					echo $resp['response'];
				} catch (Exception $e) {
					// TODO handle error
				}
			} else {
				$this->output->set_status_header('400');
				$these = implode(', ', $allowedExtensions);
				echo json_encode(array(
					'error' => "File has an invalid extension, it should be one of ". $these,
				));
			}
		} else {
			$this->output->set_status_header('400');
			echo json_encode(array("error" => "Something went horribly wrong."));
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