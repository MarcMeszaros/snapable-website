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
			$this->load->view('photo/header');
			$this->load->view('photo/index', $data);
			$this->load->view('photo/footer');
		} else {
			$this->load->view('common/html_header', $head);
			$this->load->view('common/header', $head);
			$this->load->view('photo/index', $data);
			$this->load->view('common/footer');
			$this->load->view('common/html_footer');
		}
	}

	public function get_event($event, $size='150x150') {
		$verb = 'GET';
		$path = '/event/' . $event . '/';
		$params = array(
			'size' => $size,
		);
		$headers = array(
			'Accept' => 'image/jpeg',
		);
		$resp = SnapApi::send($verb, $path, $params, $headers);

		$response = $resp['response'];
		// if the API response is successful return the data
		if ($resp['code'] == 200) {
			$this->output->set_content_type('jpeg'); // You could also use ".jpeg" which will have the full stop removed before looking in config/mimes.php
			$this->output->set_output($response);
		} 
		// otherwise return a default blank image
		else {
			$this->output->set_content_type('png');
			$this->output->set_output(file_get_contents(FCPATH.'assets/img/photo_blank.png'));
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
		// if the API response is successful return the data
		if ($resp['code'] == 200) {
			$this->output->set_content_type('jpeg'); // You could also use ".jpeg" which will have the full stop removed before looking in config/mimes.php
			$this->output->set_output($response);
		} 
		// otherwise return a default blank image
		else {
			$this->output->set_content_type('png');
			$this->output->set_output(file_get_contents(FCPATH.'assets/img/photo_blank.png'));
		}
	}

	public function delete_photo($photo) {
		$this->output->set_status_header('301');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */