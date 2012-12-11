<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct()
	{
    	parent::__construct(); 
    	$this->data['css'] = array('assets/css/setup.css', 'assets/css/dashboard.css');	
    	$this->data['js'] = array('assets/js/dashboard.js');			    	
	}
	
	public function index()
	{
		$data = array(
			'css' => $this->data['css'],
			'js' => $this->data['js'],
			'url' => 'bigger-awesomer-event'
		);
		$this->load->view('common/html_header', $data);
		$this->load->view('dashboard/index', $data);
		$this->load->view('common/html_footer', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */