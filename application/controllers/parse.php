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
	
	function text()
	{
		if ( isset($_POST['message']) )
		{
			$message = explode("\n", $_POST['message']);
			if ( empty($message) )
			{
				echo '{ "status":404 }';
			} else {
				$added = 0;
				foreach ( $message as $m )
				{
					$status = $this->event_model->addManualGuests($m,$_POST['eventURI']);
					if ( $status > 0)
					{
						$added++;
					}
				}
				if ( $added > 0 )
				{
					echo '{ "status":200 }';
				} else {
					echo '{ "status":404 }';
				}
			}
		} else {
			echo '{ "status":404 }';
		}
	}
	
}