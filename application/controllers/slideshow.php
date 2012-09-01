<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slideshow extends CI_Controller {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('event_model','',TRUE);	 	    	
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
			$deets = json_decode($this->event_model->getEventDetailsFromURL($_GET['url']));
			
			if ( $deets->status == 200 && $deets->event->photos > 0 )
			{
				$resource_uri = explode("/", $deets->event->resource_uri);
				$event_id = $resource_uri[3];
				$photos = $this->event_model->getEventsPhotos($event_id);
				echo $photos;
			} else {
				echo '{ "status": 404 }';
			}
			/*
			echo '{ 
				"id": 60, 
				"caption": "Hello World",
				"photographer": "Andrew D.",
				"timestamp": "3 minutes ago"
			}';
			*/
		} else {
			show_404();
		}
	}
	
}