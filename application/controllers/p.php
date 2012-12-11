<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class P extends CI_Controller {

	function __construct()
	{
    	parent::__construct();    	
    	//$this->load->model('photo_model','',TRUE);		    	
	}
	 
	public function index()
	{
		show_404();
	}

	public function load_photo($photo) {
		echo "&nbsp;";
		$head = array(
			'css' => array(
				'assets/css/setup.css',
				'assets/css/header.css',
				'assets/css/photo.css',
				'assets/css/footer.css',
			),
			'url' => 'blank'
		);
		
		$this->load->model('photo_model','',TRUE);
		$photo_deets = json_decode($this->photo_model->deets($photo));
		
		$data = array(
			'photo_id' => $photo,
			'caption' => $photo_deets->caption,
			'photographer' => $photo_deets->photographer,
			'date' => $photo_deets->date,
			'event_url' => $photo_deets->event_url,
			'event_name' => $photo_deets->event_name
		);
		
		if ( IS_AJAX )
		{
			$this->load->view('common/html_header');
			$this->load->view('photo/header');
			$this->load->view('photo/index', $data);
			$this->load->view('photo/footer');
			$this->load->view('common/html_footer');
		} else {
			$this->load->view('common/html_header', $head);
			$this->load->view('common/header', $head);
			$this->load->view('photo/index', $data);
			$this->load->view('common/footer');
			$this->load->view('common/html_footer');
		}
	}

	public function get_photo($photo, $size=null) {
		$verb = 'GET';
		$path = '/photo/' . $photo . '/';
		$params = array();
		if (isset($size)) {
			$params['size'] = $size;
		}
		$headers = array(
			'Accept' => 'image/jpeg',
		);
		$resp = SnapApi::send($verb, $path, $params, $headers);

		$response = $resp['response'];
		$this->output->set_content_type('jpeg'); // You could also use ".jpeg" which will have the full stop removed before looking in config/mimes.php
        $this->output->set_output($response);
	}

	public function delete_photo($photo) {
		if ($this->session->userdata('logged_in')) {
			$session_owner = $this->session->userdata('logged_in');

			// get photo details
			$verb = 'GET';
			$path = '/photo/' . $photo . '/';
			$resp = SnapApi::send($verb, $path);
			$result = json_decode($resp['response']);
			$photoEventParts = explode('/', $result->event);
			
			// get event session details
			$session_event = $this->session->userdata('event_deets');
			$eventUriParts = explode('/', $session_event['resource_uri']);

			// make sure the the user is logged in as the event owner
			if ( $session_owner['loggedin'] == true && $eventUriParts[3] == $photoEventParts[3] && IS_AJAX) {
				$verb = 'DELETE';
				$path = '/photo/' . $photo . '/';
				$resp = SnapApi::send($verb, $path);

				echo json_encode(array('status' => $resp['code']));
			} else {
				show_404();
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */