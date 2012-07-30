<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {

	function __construct()
	{
    	parent::__construct(); 	    	
	}
	
	public function index()
	{
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array("jpeg","jpg");
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
				// URL on which we have to post data
				$url = "http://devapi.snapable.com/private_v1/photo/";
				
				// Data to send
				$post_data = array();
				$post_data['event'] = $event;
				$post_data['guest'] = $guest;
				$post_data['type'] = $type;
				// The Photo
				$post_data['image'] = "@" . $_FILES['mf_file_uploadArea']['tmp_name'];
				 
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: multipart/form-data'));
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
				$response = curl_exec($ch);
				$result = '{"status":200,"result":' . $response . '}';
			}
			
			echo $result;
		} else {
			echo '{"error":"Something went horribly wrong."}';
		}
	}
}