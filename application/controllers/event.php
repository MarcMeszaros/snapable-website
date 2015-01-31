<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event extends CI_Controller {

	function __construct() {
    	parent::__construct();
    	$this->load->library('email');
    	$this->load->model('event_model','',TRUE);
	} 
	 
	public function index() {
		show_404();
	}

	public function load_event($url) {
	 	require_https();
	 	$verb = 'GET';
		$path = '/event/';
		$params = array(
			'url' => $url,
		);
		$resp = SnapApi::send($verb, $path, $params);
	 	$response = json_decode($resp['response']);

	 	// get the event
	 	$event = $response->objects[0];

	 	// if start and end dates are the same day $display_timedate is in the format "Tue July 31, 7-9 PM"
		// if start and end dates are different days $display_timedate is in the format "Tue July 31, 7 PM to Thu Aug 2, 9PM"
		$start_epoch = strtotime($event->start);
		$start_epoch_with_tz = strtotime($event->start) + ($event->tz_offset * 60);
		$end_epoch = strtotime($event->end);
		$end_epoch_with_tz = strtotime($event->end) + ($event->tz_offset * 60);
		$human_start = date("D M j, g:i A", $start_epoch_with_tz);
		$human_end = date("D M j, g:i A", $end_epoch_with_tz);
		if ( date("m-d", $start_epoch) == date("m-d", $end_epoch) )
		{
			$event->display_timedate = date("D M j", $start_epoch) . ", " . date("g:i A", $start_epoch_with_tz) . " - " . date("g:i A", $end_epoch_with_tz);
		} else {
			$event->$display_timedate = date("D M j, g:i A", $start_epoch_with_tz) . " to " . date("D M j, g:i A", $end_epoch_with_tz);
		}

		$event->human_start = $human_start;
		$event->human_end = $human_end;
		$event->start_epoch = $start_epoch;
		$event->end_epoch = $end_epoch;

	 	// don't show disabled events
	 	if($event->is_enabled == false) {
	 		show_404();
	 	}

		$head = array(
			'noTagline' => true,
			'ext_css' => array(
				'//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.0.0/css/datepicker.css',
			),
			'css' => array(
				'assets/css/signin.css',
				'assets/css/timePicker.css',
				'assets/css/header.css',
				'assets/css/event/event.css',
				'assets/css/footer.css',
				'assets/css/event/photostream-nav.css',
			),
			'ext_js' => array(
				'//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.0.0/js/bootstrap-datepicker.min.js',
				'//cdnjs.cloudflare.com/ajax/libs/mustache.js/0.7.0/mustache.min.js',
				'//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.32/jquery.form.js',
				'//maps.googleapis.com/maps/api/js?key=AIzaSyAofUaaxFh5DUuOdZHmoWETZNAzP1QEya0&sensor=false',
			),
			'js' => array(
				'assets/js/libs/jquery-Mustache.js',
				'assets/js/libs/jquery.timePicker.min.js',
				'assets/js/event/photostream.js',
				'assets/js/event/photostream-settings.js',
				'assets/js/event/photostream-guests.js',
				'assets/js/event/photostream-upload.js',
				'assets/js/event/photostream-contact.js',
				'assets/js/event/photostream-tablecards.js',
				'assets/js/event/photostream-download.js',
			),
			'url' => $url,
			'title' => $event->title . ", via Snapable"
		);

		$error = ( isset($_GET['error']) ) ? true:false;
		$data = array(
			'url' => $url,
			'event' => $event,
			'event_pk' => SnapApi::resource_pk($event->resource_uri),
		);
		if (isset($event->addresses[0])) {
			$data['address_pk'] = SnapApi::resource_pk($event->addresses[0]->{'resource_uri'});
			$data['address'] = $event->addresses[0]->{'address'};
			$data['address_uri'] = $event->addresses[0]->{'resource_uri'};
			$data['address_lat'] = $event->addresses[0]->{'lat'};
			$data['address_lng'] = $event->addresses[0]->{'lng'};
		}

		$ownerLoggedin = false;
		$guestLoggedin = false;
		if ($resp['status'] == 200) {
			$session_owner = SnapAuth::is_logged_in();
			$session_guest = SnapAuth::is_guest_logged_in();

			if ($session_owner && $event->user == $session_owner['resource_uri']) {
				$verb = 'GET';
				$path = 'user/'.SnapApi::resource_pk($event->user);
				$user_resp = SnapApi::send($verb, $path);
				$user = json_decode($user_resp['response']);

				$ownerLoggedin = true;
				$full_name = $user->first_name.' '.$user->last_name;
				$data['owner_email'] = $full_name.' <'.$user->email.'>'; 
				$head['display_name'] = $full_name;
				$head['signout_url'] = '/account/signout';

				// get the owner guest_id
				// if email address is not already a guest add
				$verb = 'GET';
				$path = '/guest/';
				$params = array(
					'event' => SnapApi::resource_pk($event->resource_uri),
					'email' => $session_owner['email'],
				);
				$resp = SnapApi::send($verb, $path, $params);
				$response = json_decode($resp['response']);

				// the owner guestID was found
				if ($resp['code'] == 200 && count($response->objects) > 0) {
			        // set data for the event view
			        $data['guest_uri'] = $response->objects[0]->resource_uri;
				}
			} else if($session_guest) {
				$guestLoggedin = true;
				$head['signout_url'] = '/event/' . $url . '/signout';
				$head['display_name'] = 'Guest';
				// set data for event view
				if (!empty($session_guest['id'])) {
					$data['guest_uri'] =  SnapApi::resource_uri('guest', $session_guest['id']);

					$verb = 'GET';
					$path = '/guest/'.$session_guest['id'].'/';
					$resp = SnapApi::send($verb, $path);
					$response = json_decode($resp['response']);
					$head['display_name'] = $response->name;
				}
			}
			$data['ownerLoggedin'] = $ownerLoggedin;
			$data['guestLoggedin'] = $guestLoggedin;

			// show the correct loggin screen if required
			$this->load->view('common/html_header', $head);
			$this->load->view('common/header2', $head);
			if (!$event->is_public && ($guestLoggedin != true && $ownerLoggedin != true)) {
				$this->load->view('event/guest_signin', $data);
			} else {
				$this->load->view('event/photostream', $data);
			}
			$this->load->view('common/footer');
			$this->load->view('common/html_footer');
		} else {
			$this->load->view('common/html_header', $head);
			$this->load->view('common/header', $head);
			$this->load->view('event/error', $data);
			$this->load->view('common/footer');
			$this->load->view('common/html_footer');
		}
	
	}

	public function guest_tasks($task) {
		if ( $task == "add" ) {
			$this->load->view('event/guests-add');
		} else if ( $task == "notify" && IS_AJAX ) {
			$data = array(
				'display' => "inline"
			);
			$this->load->view('email/guest_notification_html', $data);
		} else if ( $task == "count" && IS_AJAX ) {
			echo $this->event_model->guestCount($_GET['resource_uri']);
		} else if ( $this->uri->segment(4) == "validate" && isset($_POST) ) {
			$eventDeets = json_decode($this->event_model->getEventDetailsFromURL($this->uri->segment(3)));
			$eventID = explode('/', $eventDeets->event->resource_uri);
			
			$redirect_path = '/event/' . $eventDeets->event->url;
			if (isset($_POST['upload_photo'])) {
				$redirect_path .= '#upload-photo';
			}

			// if the pins match
			if ($eventDeets->event->public || (isset($_POST['pin']) && $_POST['pin'] == $eventDeets->event->pin)) {
				// if the guest already exists
				if (SnapAuth::guest_signin($_POST['email'], $eventDeets->event->resource_uri)) {
					redirect($redirect_path);
				} else {
					// if email address is not already a guest add
					$verb = 'POST';
					$path = '/guest/';
					$params = array(
						"name" => $_POST['name'],
						"email" => $_POST['email'],
						"event" => $eventDeets->event->resource_uri,
					);
					$resp = SnapApi::send($verb, $path, $params);
					$response = $resp['response'];

					// the guest was properly created, set the session
					if ($resp['code'] == 201) {
						SnapAuth::guest_signin_nonetwork($response);
						redirect($redirect_path);
					} else {
						// there was an error creating the guest
						redirect($redirect_path . "?error");
					}
				}
			} else {
				// invalid pin
				redirect($redirect_path . "?error");
			}
		} else {
			show_404();
		}
	}

	public function event_tasks($task) {
		require_https();
		if ( $task == "signout" ) {
			SnapAuth::guest_signout();
			redirect('/event/' . $this->uri->segment(2), 'refresh');
		} else if ( $task == "slides" ) {
			// get the event details
			$deets = json_decode($this->event_model->getEventDetailsFromURL($this->uri->segment(2)));
			$event_id = SnapApi::resource_pk($deets->event->resource_uri);

			// get some photos
			$verb = 'GET';
			$path = 'photo';
			$params = array(
				'event' => $event_id,
				'streamable' => true,
			);
			$resp = SnapApi::send($verb, $path, $params);
			$photos_raw = json_decode($resp['response']);
			$photos = array_reverse($photos_raw->objects);
			$data = array(
				'photos' => $photos,
				'event_id' => $event_id,
			);

			$head = array(
				'css' => array(
					'assets/libs/superslides/superslides.css',
					'assets/css/event/slides.css',
				),
				'js' => array(
					//'assets/libs/superslides/jquery.animate-enhanced.min.js',
					'assets/libs/superslides/jquery.superslides.min.js',
					'assets/js/libs/jquery.fullscreen.min.js',
					'assets/js/event/slides.js',
				),
			);

			$this->load->view('common/html_header', $head);
			$this->load->view('event/slides', $data);
			$this->load->view('common/html_footer');
		} else if ( $task == "invites" && IS_AJAX ) {
			$this->event_model->sendInvite($_POST);
		} else if ( $task == "guest_signin" ) {
			if (SnapAuth::is_logged_in() || SnapAuth::is_guest_logged_in()) {
				redirect('/event/'.$this->uri->segment(2));
			}

			$event_details = json_decode($this->event_model->getEventDetailsFromURL($this->uri->segment(2)));
			if($event_details->event->enabled == false) {
		 		show_404();
		 	}
			
			$head = array(
				'noTagline' => true,
				'css' => array(
					'assets/css/signin.css',
					'assets/css/header.css',
					'assets/css/event/event.css',
					'assets/css/footer.css',
				),
				'ext_js' => array(
					'//cdnjs.cloudflare.com/ajax/libs/mustache.js/0.7.0/mustache.min.js'
				),
				'js' => array(
					'assets/js/event/photostream.js',
					'assets/js/event/photostream-login.js',
				),
				'url' => $event_details->event->url,
				'title' => $event_details->event->title . ", via Snapable"
			);

			$upload_photo = ($this->input->get('upload-photo')) ? true : false;
			$error = ( isset($_GET['error']) ) ? true:false;
			$data = array(
				'url' => $event_details->event->url,
				'eventDeets' => $event_details->event,
				'upload_photo' => $upload_photo,
			);

			$this->load->view('common/html_header', $head);
			$this->load->view('common/header2', $head);
			$this->load->view('event/guest_signin', $data);
			$this->load->view('common/footer');
			$this->load->view('common/html_footer');
		} else {
			show_404();
		}
	}

	public function details_tasks($task) {
		//// ADD GUEST LIST
		if ( $task == "save" ) {
			echo "saved";
		} else {
			show_404();
		}
	}

	public function get_tasks($task) {
		if ( $task == "photos" && IS_AJAX ) {
			$offset = ($this->uri->segment(5) === false) ? 0 : $this->uri->segment(5);
			$session_owner = SnapAuth::is_logged_in();

			// get the event photos
			$verb = 'GET';
			$path = '/photo/';
			$params = array(
		       'event' => $this->uri->segment(4),
		       'offset' => $offset,
		       'limit' => 50,
			);
			// only show streamable photos if not the organizer
			if(!$this->event_model->is_organizer($params['event'], $session_owner['user_uri'])) {
				$params['streamable'] = true;
			}
			$resp = SnapApi::send($verb, $path, $params);
			$this->output->set_status_header($resp['code']);
			echo  $resp['response'];
		} else if ( $task == "guests" && IS_AJAX ) {
			$guests = $this->event_model->getGuests($this->uri->segment(4));
			echo $guests;
		} else {
			show_404();
		}
	}

	public function privacy() {
		if ( IS_AJAX && isset($_POST) ) {
			$eventParts = explode('/', $_POST['event']);
			$verb = 'PUT';
			$path = '/event/'.$eventParts[3].'/';
			$params = array(
				'public' => ($_POST['privacy-setting'] == 0) ? false : true,
			);
			$resp = SnapApi::send($verb, $path, $params);
	        $this->output->set_status_header($resp['code']);
	        echo $resp['response'];
		} else {
			$this->output->set_status_header(403);
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */