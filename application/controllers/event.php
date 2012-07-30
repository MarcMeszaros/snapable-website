<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event extends CI_Controller {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('event_model','',TRUE);		    	
	}

	
	function _remap($method)
	{
		$param_offset = 2;
		
		// Default to index
		if ( ! method_exists($this, $method))
		{
		// We need one more param
		$param_offset = 1;
		$method = 'index';
		}
		
		// Since all we get is $method, load up everything else in the URI
		$params = array_slice($this->uri->rsegment_array(), $param_offset);
		
		// Call the determined method with all params
		call_user_func_array(array($this, $method), $params);
	} 
	 
	public function index()
	{
		$segments = $this->uri->total_segments();
  	
  		if ( $segments == 2 )
		{
		
		//// FIND EVENTS
		
			if ( $this->uri->segment(2) == "find" )
			{
				echo "Find Events";
			} else {
				$event_details = $this->event_model->getEventDetailsFromURL($this->uri->segment(2));
				
				echo "&nbsp;";
				$head = array(
					'noTagline' => true,
					'css' => base64_encode('assets/css/fileuploader.css,assets/css/tipsy.css,assets/css/setup.css,assets/css/header.css,assets/css/event.css,assets/css/footer.css'),
					'js' => base64_encode('assets/js/uploader.js,assets/js/jquery.tipsy.js,assets/js/photostream.js')	
				);
				$data = array(
					'url' => $this->uri->segment(2)
				);
				$this->load->view('common/header', $head);
				$this->load->view('event/photostream', $data);
				$this->load->view('common/footer');
			}
		}
		else if ( $segments == 3 )
		{
			/*
			if ( $this->uri->segment(2) == "setup" )
			{
				echo "&nbsp;";
				$head = array(
					'css' => base64_encode('assets/css/setup.css,assets/css/header.css,assets/css/buy.css,assets/css/footer-short.css'),
					'js' => base64_encode('assets/js/jquery.timePicker.min.js,assets/js/jquery-ui-1.8.21.custom.min.js,assets/js/buy.js')	
				);
				$data = array(
					'url' => $this->uri->segment(3)
				);
				$this->load->view('common/header', $head);
				$this->load->view('event/setup/index', $data);
				$this->load->view('common/footer');
			}
			else 
			*/
			
			//// ADD GUEST LIST
			
			if ( $this->uri->segment(2) == "guests" )
			{	
				if ( $this->uri->segment(3) == "add" )
				{
					$this->load->view('event/guests-add');
				} else {
					show_404();
				}
			}
			else if ( $this->uri->segment(2) == "details" )
			{
				if ( $this->uri->segment(3) == "save" )
				{
					echo "saved";
				}
			} else {
				show_404();
			}
		} 
		/*
		else if ( $segments == 4 )
		{
		
			if ( $this->uri->segment(2) == "setup" )
			{
				$this->load->view('event/setup/' . $this->uri->segment(4));
			} else {
				show_404();
			}
		} */
		else {
			show_404();
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */