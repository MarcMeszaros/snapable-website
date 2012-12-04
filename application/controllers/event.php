<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event extends CI_Controller {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('event_model','',TRUE);		    	
	} 
	 
	public function index()
	{
		show_404();
	}

	public function load_event($url) {
	 	$event_details = json_decode($this->event_model->getEventDetailsFromURL($url));
		
		$head = array(
			'noTagline' => true,
			'css' => base64_encode('assets/css/cupertino/jquery-ui-1.8.21.custom.css,assets/css/signin.css,assets/css/fileuploader.css,assets/css/jquery.jcrop.css,assets/css/timePicker.css,assets/css/facebox.css,assets/css/tipsy.css,assets/css/setup.css,assets/css/header.css,assets/css/event.css,assets/css/footer.css'),
			'js' => base64_encode('assets/js/jquery-ui-1.8.21.custom.min.js,assets/js/mustache.js,assets/js/jquery-Mustache.js,assets/js/jquery.jcrop.js,assets/js/jquery.timePicker.min.js,assets/js/uploader.js,assets/js/facebox.js,assets/js/jquery.tipsy.js,assets/js/photostream.js'),
			'url' => $event_details->event->url,
			'type' => $this->uri->segment(1),
			'title' => $event_details->event->title . ", via Snapable"
		);

		$error = ( isset($_GET['error']) ) ? true:false;
		$data = array(
			'url' => $url,
			'eventDeets' => $event_details->event
		);
		
		if ( $event_details->status == 200 )
		{
			if ($this->session->userdata('logged_in'))
			{
				$session_owner = $this->session->userdata('logged_in');
				
				if ( $session_owner['loggedin'] == true )
				{
					$data['owner'] = true;
					$session_event = $this->session->userdata('event_deets');
					$ownerLoggedin = true;
					$data["logged_in_user_resource_uri"] = $session_owner['resource_uri'];
					$head["loggedInBar"] = "owner";
					$eventID = explode("/",$event_details->event->resource_uri);

					// get the owner guest_id
					// if email address is not already a guest add
					$verb = 'GET';
					$path = '/guest/';
					$params = array(
						'event' => $eventID[3],
						'email' => $session_owner['email'],
					);
					$resp = SnapApi::send($verb, $path, $params);

					$response = json_decode($resp['response']);
					$httpcode = $resp['code'];
		
					// the guest was properly created, set the session
					if ($httpcode == 200 && count($response->objects) > 0) {
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
				$this->load->view('common/html_header', $head);
				$this->load->view('common/header2', $head);
				$this->load->view('event/guest_signin', $data);
				$this->load->view('common/footer');
				$this->load->view('common/html_footer');
			} else {
				$this->load->view('common/html_header', $head);
				$this->load->view('common/header2', $head);
				$this->load->view('event/photostream', $data);
				$this->load->view('common/footer');
				$this->load->view('common/html_footer');
			}
		} else {
			$this->load->view('common/html_header', $head);
			$this->load->view('common/header', $head);
			$this->load->view('event/error', $data);
			$this->load->view('common/footer');
			$this->load->view('common/html_footer');
		}
	
	}

	public function guest_tasks($task) {
		if ( $task == "add" )
		{
			$this->load->view('event/guests-add');
		} 
		else if ( $task == "notify" && IS_AJAX )
		{
			$data = array(
				'display' => "inline"
			);
			$this->load->view('email/guest_notification', $data);
		} 
		else if ( $task == "count" && IS_AJAX )
		{
			echo $this->event_model->guestCount($_GET['resource_uri']);
		} 
		else if ( $this->uri->segment(4) == "validate" && isset($_POST) )
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
						$path = '/guest/';
						$params = array(
							"email" => $_POST['email'],
							"event" => $eventDeets->event->resource_uri,
							"type" => "/".SnapApi::$api_version."/type/5/",
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
		else {
			show_404();
		}
	}

	public function event_tasks($task) {
		if ( $task == "signout" )
		{
			$this->session->unset_userdata('guest_login');
			//session_destroy();
			redirect('/event/' . $this->uri->segment(2), 'refresh');
		}
		else if ( $task == "slideshow" )
		{
			$event_details = json_decode($this->event_model->getEventDetailsFromURL($this->uri->segment(2)));
			
			$data = array(
				'noTagline' => true,
				'css' => base64_encode('assets/css/setup.css,assets/css/slideshow.css'),
				'js' => base64_encode('assets/js/jquery.cycle.all.js,assets/js/slideshow.js'),
				'url' => $event_details->event->url,
				'title' => $event_details->event->title . ", " . $event_details->event->display_timedate . " via Snapable"
			);
			
			$this->load->view('common/html_header', $data);
			$this->load->view('event/slideshow', $data);
			$this->load->view('common/html_footer', $data);
		} 
		else if ( $task == "invites" && IS_AJAX )
		{
			echo $this->event_model->sendInvite($_POST);
		} else {
			show_404();
		}
	}

	public function details_tasks($task) {
		//// ADD GUEST LIST
		if ( $task == "save" )
		{
			echo "saved";
		} else {
			show_404();
		}
	}

	public function get_tasks($task) {
		if ( $task == "photos" && IS_AJAX )
		{
			$offset = ($this->uri->segment(5) === false) ? 0 : $this->uri->segment(5);
			$photos = $this->event_model->getEventsPhotos($this->uri->segment(4), $offset);
			echo $photos; 
		} 
		else if ( $task == "guests" && IS_AJAX )
		{
			$guests = $this->event_model->getGuests($this->uri->segment(4));
			echo $guests;
		}
		else {
			show_404();
		}
	}

	public function find() {
		echo "Find Events";
	}

	public function privacy() {
		if ( IS_AJAX && isset($_POST) )
		{
			$event_uri = $_POST['event'];
			$setting = $_POST['selected'];
			
			echo $this->event_model->updatePrivacy($event_uri, $setting);
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */