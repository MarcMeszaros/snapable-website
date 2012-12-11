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
		require_https();
	 	$event_details = json_decode($this->event_model->getEventDetailsFromURL($url));
	 	// don't show disabled events
	 	if($event_details->event->enabled == false) {
	 		show_404();
	 	}
		
		$head = array(
			'noTagline' => true,
			'ext_css' => array(
				'//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/cupertino/jquery-ui.css',
				'//cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/0.9.10/jquery.Jcrop.min.css'
			),
			'css' => array(
				'assets/css/signin.css',
				'assets/css/fileuploader.css',
				'assets/css/timePicker.css',
				'assets/css/facebox.css',
				'assets/css/tipsy.css',
				'assets/css/setup.css',
				'assets/css/header.css',
				'assets/css/event.css',
				'assets/css/footer.css',
			),
			'ext_js' => array(
				'//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js',
				'//cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/0.9.10/jquery.Jcrop.min.js',
				'//cdnjs.cloudflare.com/ajax/libs/mustache.js/0.7.0/mustache.min.js'
			),
			'js' => array(
				'assets/js/libs/jquery-Mustache.js',
				'assets/js/libs/jquery.timePicker.min.js',
				'assets/js/libs/jquery.tipsy.js',
				'assets/js/uploader.js',
				'assets/js/facebox.js',
				'assets/js/event/photostream.js',
				'assets/js/event/photostream-nav.js',
				'assets/js/event/photostream-settings.js',
				'assets/js/event/photostream-guests.js',
				'assets/js/event/photostream-addons.js',
			),
			'url' => $event_details->event->url,
			'type' => $this->uri->segment(1),
			'title' => $event_details->event->title . ", via Snapable"
		);

		$error = ( isset($_GET['error']) ) ? true:false;
		$data = array(
			'url' => $url,
			'eventDeets' => $event_details->event
		);
		
		$ownerLoggedin = false;
		$guestLoggedin = false;
		if ( $event_details->status == 200 )
		{
			$session_owner = SnapAuth::is_logged_in();
			$session_guest = SnapAuth::is_guest_logged_in();
			if ($session_owner && $event_details->event->user == $session_owner['resource_uri'])
			{
				$data['owner'] = true;
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
			} 
			else if($session_guest)
			{
				$guestLoggedin = true;
				$head["loggedInBar"] = "guest";
				$data['typeID'] = $session_guest['type'];
			}
			
			// show the correct loggin screen if required
			if ($guestLoggedin != true && $ownerLoggedin != true)
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
			$eventID = explode('/', $eventDeets->event->resource_uri);
			
			// if the pins match
			if ($eventDeets->event->public || (isset($_POST['pin']) && $_POST['pin'] == $eventDeets->event->pin))
			{
				// if the guest already exists
				if (SnapAuth::guest_signin($_POST['email'], $eventDeets->event->resource_uri))
				{
					redirect('/event/' . $eventDeets->event->url);
				} else {
					// if email address is not already a guest add
					$verb = 'POST';
					$path = '/guest/';
					$params = array(
						"email" => $_POST['email'],
						"event" => $eventDeets->event->resource_uri,
						"type" => "/".SnapApi::$api_version."/type/6/",
					);
					$resp = SnapApi::send($verb, $path, $params);
					$response = $resp['response'];

					// the guest was properly created, set the session
					if ($resp['code'] == 201) {
						SnapAuth::guest_signin_nonetwork($response);
						redirect("/event/" . $eventDeets->event->url);
					} else {
						// there was an error creating the guest
						redirect("/event/" . $eventDeets->event->url . "?error");
					}
				}
			} else {
				// invalid pin
				redirect("/event/" . $eventDeets->event->url . "?error");
			}
		} else {
			show_404();
		}
	}

	public function event_tasks($task) {
		require_https();
		if ( $task == "signout" )
		{
			SnapAuth::guest_signout();
			redirect('/event/' . $this->uri->segment(2), 'refresh');
		}
		else if ( $task == "slideshow" )
		{
			$event_details = json_decode($this->event_model->getEventDetailsFromURL($this->uri->segment(2)));
			
			$data = array(
				'noTagline' => true,
				'css' => array('assets/css/setup.css', 'assets/css/slideshow.css'),
				'js' => array('assets/js/jquery.cycle.all.js', 'assets/js/slideshow.js'),
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