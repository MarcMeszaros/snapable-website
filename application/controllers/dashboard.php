<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct()
	{
    	parent::__construct(); 
    	echo "&nbsp;";   
    	$this->data['css'] = base64_encode('assets/css/setup.css,assets/css/dashboard.css');	
    	$this->data['js'] = base64_encode('assets/js/dashboard.js');			    	
	}
	
	public function index()
	{
		$data = array(
			'css' => $this->data['css'],
			'js' => $this->data['js'],
			'url' => 'bigger-awesomer-event'
		);
		$this->load->view('dashboard/index', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */