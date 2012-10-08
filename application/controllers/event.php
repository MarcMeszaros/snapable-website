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
			} 
			else if ( $this->uri->segment(2) == "privacy" && IS_AJAX && isset($_POST) )
			{
				$event_uri = $_POST['event'];
				$setting = $_POST['selected'];
				
				echo $this->event_model->updatePrivacy($event_uri, $setting);
			} else {
				$event_details = json_decode($this->event_model->getEventDetailsFromURL($this->uri->segment(2)));
				
				echo "&nbsp;";
				$head = array(
					'noTagline' => true,
					'css' => base64_encode('assets/css/signin.css,assets/css/fileuploader.css,assets/css/jquery.jcrop.css,assets/css/facebox.css,assets/css/tipsy.css,assets/css/setup.css,assets/css/header.css,assets/css/event.css,assets/css/footer.css'),
					'js' => base64_encode('assets/js/mustache.js,assets/js/jquery-Mustache.js,assets/js/jquery.jcrop.js,assets/js/uploader.js,assets/js/facebox.js,assets/js/jquery.tipsy.js,assets/js/photostream.js'),
					'url' => $event_details->event->url,
					'type' => $this->uri->segment(1),
					'title' => $event_details->event->title . ", " . $event_details->event->display_timedate . " via Snapable"
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
							$session_event = $this->session->userdata('event_deets');
							$ownerLoggedin = true;
							$data["logged_in_user_resource_uri"] = $session_owner['resource_uri'];
							$head["loggedInBar"] = "owner";
							$eventID = explode("/",$event_details->event->resource_uri);

							// get the owner guest_id
							// if email address is not already a guest add
							$verb = 'GET';
							$path = '/private_v1/guest/';
							$params = array(
								'event' => $eventID[3],
								'email' => $session_owner['email'],
							);
							$resp = SnapApi::send($verb, $path, $params);

							$response = $resp['response'];
							$httpcode = $resp['code'];
							$response = json_decode($response);
				
							// the guest was properly created, set the session
							if ($httpcode == 200) {
								$guestID = explode("/", $response->objects[0]->resource_uri);
								$session_owner['guest_id'] = $guestID[3];
						        $this->session->set_userdata('logged_in', $session_owner);
							}
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
							$data['typeID'] = $session_guest['type'];
						} else {
							$guestLoggedin = false;
						}
					} else {
						
					}
					
					// show the correct loggin screen if required
					if ($event_details->event->privacy < 6 && ((!isset($guestLoggedin) || $guestLoggedin != true) && (!isset($ownerLoggedin) || $ownerLoggedin != true)))
					{
						$this->load->view('common/header2', $head);
						$this->load->view('event/guest_signin', $data);
						$this->load->view('common/footer');
					} else {
						$this->load->view('common/header2', $head);
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
				} 
				else if ( $this->uri->segment(3) == "notify" && IS_AJAX )
				{
					$data = array(
						'display' => "inline"
					);
					$this->load->view('email/guest_notification', $data);
				} 
				else if ( $this->uri->segment(3) == "count" && IS_AJAX )
				{
					echo $this->event_model->guestCount($_GET['resource_uri']);
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
			}
			else if ( $this->uri->segment(3) == "slideshow" )
			{
				$event_details = json_decode($this->event_model->getEventDetailsFromURL($this->uri->segment(2)));
				
				echo "&nbsp;";
				$data = array(
					'noTagline' => true,
					'css' => base64_encode('assets/css/setup.css,assets/css/slideshow.css'),
					'js' => base64_encode('assets/js/jquery.cycle.all.js,assets/js/slideshow.js'),
					'url' => $event_details->event->url,
					'title' => $event_details->event->title . ", " . $event_details->event->display_timedate . " via Snapable"
				);
				
				$this->load->view('event/slideshow', $data);
			} 
			else if ( $this->uri->segment(3) == "invites" && IS_AJAX )
			{
				echo $this->event_model->sendInvite($_POST);
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
								$guestID = explode("/", $validation->resource_uri);
								$sess_array = array(
								  'id' => $guestID[3],
								  'name' => $validation->name,
						          'email' => $_POST['email'],
						          'type' => $validation->type,
						          'loggedin' => true
						        );
						        $this->session->set_userdata('guest_login', $sess_array);
								redirect("/event/" . $eventDeets->event->url);
							} 
							else if ( $validation->status == 404 ) {
								
								// if email address is not already a guest add
								$verb = 'POST';
								$path = '/private_v1/guest/';
								$params = array(
									"email" => $_POST['email'],
									"event" => $eventDeets->event->resource_uri,
									"type" => "/private_v1/type/5/",
								);
								$resp = SnapApi::send($verb, $path, $params);
								
								$response = $resp['response'];
								$httpcode = $resp['code'];

								// the guest was properly created, set the session
								if ($httpcode == 201) {
									$guestID = explode("/", $response->objects[0]->resource_uri);
									$sess_array = array(
									  'id' => $guestID[3],
									  'name' => '',
							          'email' => $_POST['email'],
							          'type' => '5',
							          'loggedin' => true
							        );
							        $this->session->set_userdata('guest_login', $sess_array);
									redirect("/event/" . $eventDeets->event->url);
								} 
								// there was an error creating the guest
								else {
									redirect("/event/" . $eventDeets->event->url . "?error");
								}
							} 
							else {
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
			else if ( $this->uri->segment(2) == "get" && $this->uri->segment(3) == "photos" && IS_AJAX )
			{
				$photos = $this->event_model->getEventsPhotos($this->uri->segment(4));
				echo $photos; 
			} 
			else if ( $this->uri->segment(2) == "get" && $this->uri->segment(3) == "guests" && IS_AJAX )
			{
				$guests = $this->event_model->getGuests($this->uri->segment(4));
				echo $guests;
			}
		} else {
			show_404();
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */