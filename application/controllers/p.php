<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class P extends CI_Controller {

	function __construct()
	{
    	parent::__construct();    	
    	//$this->load->model('photo_model','',TRUE);		    	
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
			echo "&nbsp;";
			$head = array(
				'css' => base64_encode('assets/css/setup.css,assets/css/header.css,assets/css/photo.css,assets/css/footer.css'),
				'url' => 'blank'
			);
			
			$this->load->model('photo_model','',TRUE);
			$photo_deets = json_decode($this->photo_model->deets($this->uri->segment(2)));
			
			$data = array(
				'photo_id' => $this->uri->segment(2),
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
				$this->load->view('common/header', $head);
				$this->load->view('photo/index', $data);
				$this->load->view('common/footer');
			}
		} 
		else if ( $segments == 3 )
		{
			if ( $this->uri->segment(2) == "get" )
			{
				$verb = 'GET';
				$path = '/private_v1/photo/' . $this->uri->segment(3) . '/';
				$params = array();
				$headers = array(
					'Accept' => 'image/jpeg',
				);
				$resp = SnapApi::send($verb, $path, $params, $headers);

				$response = $resp['response'];

				echo $response;
			} else {
				show_404();
			}
		}
		else if ( $segments == 4 )
		{
			if ( $this->uri->segment(2) == "get" )
			{
				$verb = 'GET';
				$path = '/private_v1/photo/' . $this->uri->segment(3) . '/';
				$params = array(
					'size' => $this->uri->segment(4),
				);
				$headers = array(
					'Accept' => 'image/jpeg',
				);
				$resp = SnapApi::send($verb, $path, $params, $headers);

				$response = $resp['response'];

				echo $response;
			} else {
				show_404();
			}
		}  else {
			show_404();
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */