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
					if (!empty($_POST['guest'])) {
						$params['guest'] = $_POST['guest'];
					}
					if (!empty($_POST['caption'])) {
						$params['caption'] = $_POST['caption'];
					}
					$headers = array(
						'Content-Type' => 'multipart/form-data',
					);
					$resp = SnapApi::send($verb, $path, $params, $headers);

					unlink($tmp_file);
					$this->output->set_status_header($resp['code']);
					echo $resp['response'];
				} catch (Exception $e) {
					unlink($tmp_file);
					$this->output->set_status_header('500');
					echo json_encode(array("error" => "Something went horribly wrong with the website."));
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
		
		if ( isset($_FILES['guests-file-input']) ) {
			ini_set('auto_detect_line_endings', true);
			
			$filename = $_FILES['guests-file-input']['name']; // Get the name of the file (including file extension).
			$ext = substr($filename, strpos($filename,'.')+1, strlen($filename)-1); // Get the extension from the filename.
			$tmp_file = $_FILES['guests-file-input']['tmp_name'];

			try {
		       	// if we can open the file
		       	$guests = array();
				if (($handle = fopen($tmp_file, "r")) !== FALSE) {
					// loop through all the CSV rows
				    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				        $num = count($data); // number of fields

				    	// some loop variables
				    	$name = '';
				    	$email = '';
				    	// build the guest entry
			        	for ($c=0; $c < $num; $c++) {
			        	    // check if is email address
			        	    if( preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $data[$c]) > 0){
			        	    	// is email
			        	    	$email = $data[$c];
			        	    } else {
				        	    // isn't an email, must be a name
					        	$name = $data[$c];
			        	    }
			        	}

			        	// add the guest entry to the array
			        	if (strlen($email) > 0) {
			        		$guests[$email] = $name;
			        	}
				    }
				    fclose($handle); // close the csv
				    unlink($tmp_file);
				}

				// create the guest entries
				$verb = 'POST';
				$path = 'guest';
				$httpCode = 201;
				$response = '';
				foreach ($guests as $email => $name) {
					$params = array(
						'email' => trim($email),
						'name' => trim($name),
						'event' => SnapApi::resource_uri('event', $_POST['event_id']),
					);
					$resp = SnapApi::send($verb, $path, $params);

					// update the status code
					if ($resp['code'] > 202) {
						$httpCode = $resp['code'];
						$response = $resp['response'];
					}
				}

				// set the response header
				$this->output->set_status_header($httpCode);
				if (strlen($response) > 0) {
					echo $response;
				}
			} catch (Exception $e) {
				unlink($tmp_file);
				$this->output->set_status_header('500');
				echo json_encode(array("error" => "Something went horribly wrong with the website."));
			}
		}
	}

	function text()
	{
		// sanity check
		if (!isset($_POST['message']) || empty($_POST['message'])) {
			$this->output->set_status_header(400);
			return;
		} 
		$message = explode("\n", $_POST['message']);

		$guests = array();
		foreach ( $message as $line ) {
			// only process valid lines
			if (stristr($line, ',')) {
				$details = explode(',', $line);
				// some loop variables
				$name = '';
				$email = '';
				foreach ($details as $data) {
					// check if is email address
					$data = trim($data);
					if( preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $data) > 0){
						$email = $data; // is email
					} else if (strlen($data) > 0) {
						$name = $data; // isn't an email, must be a name
					}
				}
				// add the guest entry to the array
				if (strlen($email) > 0) {
					$guests[$email] = $name;
				}
			}
		}

		// GET LIST OF CURRENT GUESTS
		$verb = 'GET';
		$path = '/guest/';
		$params = array(
			'event' => $_POST['event_id'],
		);
		$resp = SnapApi::send($verb, $path, $params);
		$result = json_decode($resp['response']);
	
		// build a list of existing emails
		$existingGuests = array();	
		if ( $result->meta->total_count > 0 ) {
			foreach ( $result->objects as $guest ) {
				$existingGuests[] = $guest->email;
			}
		}

		// create/update the guest entries
		$path = 'guest';
		$httpCode = 201;
		$response = '';
		foreach ($guests as $email => $name) {
			$verb = (in_array($email, $existingGuests)) ? 'PATCH' : 'POST';
			$params = array(
				'email' => trim($email),
				'name' => trim($name),
				'event' => SnapApi::resource_uri('event', $_POST['event_id']),
			);
			$resp = SnapApi::send($verb, $path, $params);

			// update the status code
			if ($resp['code'] > 201) {
				$httpCode = $resp['code'];
			}
			if ($resp['code'] > 202) {
				$response = $resp['response'];
			}
		}

		// set the response header
		$this->output->set_status_header($httpCode);
		if (strlen($response) > 0) {
			echo $response;
		}
	}
}