<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slideshow extends CI_Controller {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('photo_model','',TRUE);	 	    	
	}
	
	public function index()
	{
		show_404();
	}
	
	function photos()
	{
		$segments = $this->uri->total_segments();
		
		if ( IS_AJAX && isset($_GET['url']) )
		{
			echo '{ 
				"id": 60, 
				"caption": "Hello World",
				"photographer": "Andrew D.",
				"timestamp": "3 minutes ago"
			}';
		} else {
			show_404();
		}
	}
	
}