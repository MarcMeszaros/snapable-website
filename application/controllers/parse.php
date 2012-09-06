<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parse extends CI_Controller {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('event_model','',TRUE); 	    	
	}
	
	public function index()
	{
		show_404();
	}
	
	function csv()
	{
		if ( IS_AJAX && isset($_POST) && isset($_POST['file']) )
		{
			echo $this->event_model->addGuests($_POST);
		} else {
			show_404();
		}
	}
	
}