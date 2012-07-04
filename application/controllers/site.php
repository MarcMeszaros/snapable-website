<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	function __construct()
	{
    	parent::__construct(); 
    	echo "&nbsp;";   
    	$this->data['css'] = base64_encode('assets/css/setup.css,assets/css/header.css,assets/css/buy.css,assets/css/footer.css');			    	
	}
	
	public function index()
	{
		show_404();
	}
	
	public function contact()
	{
		$head = array(
			'css' => $this->data['css']
		);
		$this->load->view('common/header', $head);
		$this->load->view('site/contact');
		$this->load->view('common/footer');
	}
	
	public function faq()
	{	
		$data = array(
			'css' => $this->data['css']
		);
		$this->load->view('common/header', $data);
		$this->load->view('site/faq');
		$this->load->view('common/footer');
	}
	
	public function terms()
	{
		$head = array(
			'css' => $this->data['css']
		);
		$this->load->view('common/header', $head);
		$this->load->view('site/terms');
		$this->load->view('common/footer');
	}
	
	public function privacy()
	{
		$head = array(
			'css' => $this->data['css']
		);
		$this->load->view('common/header', $head);
		$this->load->view('site/privacy');
		$this->load->view('common/footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */