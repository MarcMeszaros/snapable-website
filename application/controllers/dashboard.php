<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct()
	{
    	parent::__construct(); 
    	echo "&nbsp;";   
    	$this->data['css'] = base64_encode('assets/css/facebox.css,assets/css/setup.css,assets/css/header.css,assets/css/dashboard.css,assets/css/footer.css');	
    	$this->data['js'] = base64_encode('assets/js/jquery-1.7.2.min.js,assets/js/facebox.js,assets/js/dashboard.js');			    	
	}
	
	public function index()
	{
		echo "&nbsp;";
		$head = array(
			'css' => $this->data['css'],
			'js' => $this->data['js']
		);
		$data = array(
			'url' => 'bigger-awesomer-event'
		);
		$this->load->view('common/header', $head);
		$this->load->view('dashboard/index', $data);
		$this->load->view('common/footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */