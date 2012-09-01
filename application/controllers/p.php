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
				//http://devapi.snapable.com/private_v1/photo/schema/
				$url = "http://devapi.snapable.com/private_v1/photo/" . $this->uri->segment(3) . "/";
			
				$length = 8;
				$nonce = "";
				while ($length > 0) {
				    $nonce .= dechex(mt_rand(0,15));
				    $length -= 1;
				}
				
				$api_key = 'abc123';
				$api_secret = '123';
				$verb = 'GET';
				$path = '/private_v1/photo/' . $this->uri->segment(3) . '/';
				$x_path_nonce = $nonce;
				$x_snap_date = gmdate("Ymd", time()) . 'T' . gmdate("His", time()) . 'Z';
				
				$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
				$signature = hash_hmac('sha1', $raw_signature, $api_secret);
				 
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Accept: image/jpeg',
					'X-SNAP-Date: ' . $x_snap_date ,
					'X-SNAP-nonce: ' . $x_path_nonce ,
					'Authorization: SNAP ' . $api_key . ':' . $signature 
		    	));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
				$response = curl_exec($ch);
				
				header('Content-Type: image/jpeg');
				echo $response;
			} else {
				show_404();
			}
		}
		else if ( $segments == 4 )
		{
			if ( $this->uri->segment(2) == "get" )
			{
				//http://devapi.snapable.com/private_v1/photo/schema/
				$url = "http://devapi.snapable.com/private_v1/photo/" . $this->uri->segment(3) . "/";
			
				$length = 8;
				$nonce = "";
				while ($length > 0) {
				    $nonce .= dechex(mt_rand(0,15));
				    $length -= 1;
				}
				
				$api_key = 'abc123';
				$api_secret = '123';
				$verb = 'GET';
				$path = '/private_v1/photo/' . $this->uri->segment(3) . '/';
				$x_path_nonce = $nonce;
				$x_snap_date = gmdate("Ymd", time()) . 'T' . gmdate("His", time()) . 'Z';
				
				$raw_signature = $api_key . $verb . $path . $x_path_nonce . $x_snap_date;
				$signature = hash_hmac('sha1', $raw_signature, $api_secret);
				 
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url . '?size=' . $this->uri->segment(4) );
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Accept: image/jpeg',
					'X-SNAP-Date: ' . $x_snap_date ,
					'X-SNAP-nonce: ' . $x_path_nonce ,
					'Authorization: SNAP ' . $api_key . ':' . $signature 
		    	));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
				$response = curl_exec($ch);
				
				header('Content-Type: image/jpeg');
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