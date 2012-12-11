<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	function __construct()
	{
    	parent::__construct(); 
    	$this->data['css'] = array(
    		'assets/css/setup.css',
    		'assets/css/header.css',
    		'assets/css/site.css',
    		'assets/css/footer.css',
    	);			    	
	}
	
	public function index()
	{
		show_404();
	}
	
	public function contact()
	{
		$head = array(
			'css' => $this->data['css'],
			'url' => 'blank',
			'active' => 'contact'
		);
		$this->load->view('common/header-site', $head);
		$this->load->view('site/contact');
		$this->load->view('common/footer-site');
	}
	
	public function faq()
	{	
		$data = array(
			'css' => $this->data['css'],
			'active' => 'faq'
		);
		$this->load->view('common/header-site', $data);
		$this->load->view('site/faq');
		$this->load->view('common/footer-site');
	}
	
	public function terms()
	{
		$head = array(
			'css' => $this->data['css'],
			'url' => 'blank',
			'active' => 'terms'
		);
		$this->load->view('common/header-site', $head);
		$this->load->view('site/terms');
		$this->load->view('common/footer-site');
	}
	
	public function privacy()
	{
		$head = array(
			'css' => $this->data['css'],
			'url' => 'blank',
			'active' => 'privacy'
		);
		$this->load->view('common/header-site', $head);
		$this->load->view('site/privacy');
		$this->load->view('common/footer-site');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */