<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Min extends CI_Controller {

	function __construct()
	{
    	parent::__construct();
    	// MINIFY CSS
    	$this->load->driver('minify');    	
	}

	public function index()
	{
		show_404();
	}

	public function c($base64_files) {
		$files = base64_decode($base64_files);
		$combine = explode(",",$files);
        header('Cache-Control: max-age=28800'); // 8 hours
		header('Content-Type: text/css');
		echo $this->minify->combine_files($combine);
	}

	public function j($base64_files) {
		$files = base64_decode($base64_files);
		$combine = explode(",",$files);
        header('Cache-Control: max-age=28800'); // 8 hours
		header('Content-Type: application/javascript'); 
		echo $this->minify->combine_files($combine);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */