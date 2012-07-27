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
		
		//print_r($_FILES['file']);
		/*
		if ( !function_exists('sys_get_temp_dir')) {
		  function sys_get_temp_dir() {
		      if( $temp=getenv('TMP') )        return $temp;
		      if( $temp=getenv('TEMP') )        return $temp;
		      if( $temp=getenv('TMPDIR') )    return $temp;
		      $temp=tempnam(__FILE__,'');
		      if (file_exists($temp)) {
		          unlink($temp);
		          return dirname($temp);
		      }
		      return null;
		  }
		 }
		
		 echo realpath(sys_get_temp_dir());
		 */
		 
		 
		$upload_path = '/tmp-files/'; 
		if(!is_writable($upload_path))
		{
			echo "Upload path is not writeable";
		}
		 
		if ( isset($_REQUEST['qqfile']) && isset($_REQUEST['event']) && isset($_REQUEST['guest']) && isset($_REQUEST['type']))
		{
			$image = $_REQUEST['image'];
			$event = $_REQUEST['event'];
			$guest = $_REQUEST['guest'];
			$type = $_REQUEST['type'];
			
			$filename = $_FILES['image']['name']; // Get the name of the file (including file extension).
			$ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
			$tmp_file = $_FILES['image']['tmp_name'];
			
			if(!in_array($ext,$allowedExtensions))
			{
				$these = implode(', ', $allowedExtensions);
				$result = array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
			} else {
				$result = array('success'=>true); //$uploader->handleUpload('uploads/');
			}
			
			echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		} else {
			echo '{"error":"Something went horribly wrong."}';
		}
	}
}