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
				$event_details = json_decode($this->event_model->getEventDetailsFromURL($this->uri->segment(2)));
				
				echo "&nbsp;";
				$head = array(
					'noTagline' => true,
					'css' => base64_encode('assets/css/signin.css,assets/css/fileuploader.css,assets/css/tipsy.css,assets/css/setup.css,assets/css/header.css,assets/css/event.css,assets/css/footer.css'),
					'js' => base64_encode('assets/js/uploader.js,assets/js/jquery.tipsy.js,assets/js/photostream.js'),
					'url' => $event_details->event->url	
				);
				
				if ( isset($_GET['error']) )
				{
					$error = true;
				} else {
					$error = false;
				}
				
				$data = array(
					'url' => $this->uri->segment(2),
					'eventDeets' => $event_details->event
				);
				
				if ( $event_details->status == 200 )
				{
					if ($this->session->userdata('logged_in'))
					{
						$session_owner = $this->session->userdata('logged_in');
						
						if ( $session_owner['loggedin'] == true )
						{
							$ownerLoggedin = true;
							$head["loggedInBar"] = "owner"; 
						} else {
							$ownerLoggedin = false;
						}
					} 
					else if($this->session->userdata('guest_login'))
					{
						$session_guest = $this->session->userdata('guest_login');
						
						if ( $session_guest['loggedin'] == true )
						{
							$guestLoggedin = true;
							$head["loggedInBar"] = "guest"; 
						} else {
							$guestLoggedin = false;
						}
					} else {
						
					}
					
					if ( $event_details->event->privacy < 6 && ( isset($guestLoggedin) && $guestLoggedin != true ) )
					{
						$this->load->view('common/header2', $head);
						$this->load->view('event/guest_signin', $data);
						$this->load->view('common/footer');
					} else {
						$this->load->view('common/header', $head);
						$this->load->view('event/photostream', $data);
						$this->load->view('common/footer');
					}
				} else {
					$this->load->view('common/header', $head);
					$this->load->view('event/error', $data);
					$this->load->view('common/footer');
				}
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
			} 
			else if ( $this->uri->segment(3) == "signout" )
			{
				$this->session->unset_userdata('guest_login');
				//session_destroy();
				redirect('/event/' . $this->uri->segment(2), 'refresh');
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
		else if ( $segments == 4 )
		{
			if ( $this->uri->segment(2) == "guest" )
			{
				if ( $this->uri->segment(4) == "validate" && isset($_POST) )
				{
					$eventDeets = json_decode($this->event_model->getEventDetailsFromURL($this->uri->segment(3)));
					
					if ( $eventDeets->event->enabled == 1 )
					{
						if ( isset($_POST['pin']) && $_POST['pin'] == $eventDeets->event->pin )
						{
							$validation = json_decode($this->event_model->validateGuest($eventDeets, $_POST['email'], $_POST['pin']));
							
							if ( $validation->status == 200 )
							{
								$sess_array = array(
								  'name' => $validation->name,
						          'email' => $_POST['email'],
						          'loggedin' => true
						        );
						        $this->session->set_userdata('guest_login', $sess_array);
								redirect("/event/" . $eventDeets->event->url);
							} else {
								redirect("/event/" . $eventDeets->event->url . "?error");
							}
						} else {
							redirect("/event/" . $eventDeets->event->url . "?error");
						}
					} else {
						echo "This event has not yet been published by the event owner.";
					}
				}
			}  
		} else {
			show_404();
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */