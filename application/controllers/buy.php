<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buy extends CI_Controller {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('buy_model','',TRUE);		    	
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
			if ( $this->uri->segment(2) == "complete" )
			{
				if ( isset($_POST['event']) && isset($_POST['user']) && isset($_POST['cc']) && isset($_POST['address']) )
				{
					$data = array(
						'title' => $_POST['event']['title']
					);
					$this->load->view('buy/complete', $data);
					
					$create_event = $this->buy_model->createEvent($_POST['event'], $_POST['user'], $_POST['cc'], $_POST['address']);
					
					if ( $create_event == 1 )
					{
						redirect('/dashboard'); //redirect('/event/setup/' . $_POST['event']['url']);
					} else {
						redirect('/buy/error');
					}
				} else {
					show_404();
				}
			} else {
				$data = array(
					'package' => $this->buy_model->getPackageDetails($this->uri->segment(2))
				);
				
				if( empty($data['package']) )
				{
					redirect('../', 'refresh');
				} else {	
					echo "&nbsp;";
					$head = array(
						'linkHome' => true,
						'css' => base64_encode('assets/css/cupertino/jquery-ui-1.8.21.custom.css,assets/css/timePicker.css,assets/css/setup.css,assets/css/header.css,assets/css/buy.css,assets/css/footer-short.css'),
						'js' => base64_encode('assets/js/jquery-1.7.2.min.js,assets/js/jquery-ui-1.8.21.custom.min.js,assets/js/jquery.timePicker.min.js,assets/js/buy.js')	
					);
					$this->load->view('common/header', $head);
					$this->load->view('buy/index', $data);
					$this->load->view('common/footer');
				}
			}
		} else {
			show_404();
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */