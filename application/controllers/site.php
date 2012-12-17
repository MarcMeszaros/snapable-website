<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	function __construct()
	{
    	parent::__construct();
    	$this->head['css'] = array(
    		'assets/css/site.css',
    	);
    	$this->head['ext_js'] = array(
    		'//cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js',
    		'//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.20/jquery.form.js',
    	);
    	$this->head['js'] = array(
    		'assets/js/site/site.js',
    	);
	}
	
	public function index()
	{
		show_404();
	}
	
	public function contact()
	{
		$data = array(
			'url' => 'blank',
			'active' => 'contact'
		);
		$this->load->view('common/html_header', $this->head);
		$this->load->view('common/header-site', $data);
		$this->load->view('site/contact');
		$this->load->view('common/footer-site');
		$this->load->view('common/html_footer');
	}
	
	public function faq()
	{
		$data = array(
			'active' => 'faq'
		);
		$this->load->view('common/html_header', $this->head);
		$this->load->view('common/header-site', $data);
		$this->load->view('site/faq');
		$this->load->view('common/footer-site');
		$this->load->view('common/html_footer');
	}
	
	public function terms()
	{
		$data = array(
			'url' => 'blank',
			'active' => 'terms'
		);
		$this->load->view('common/html_header', $this->head);
		$this->load->view('common/header-site', $data);
		$this->load->view('site/terms');
		$this->load->view('common/footer-site');
		$this->load->view('common/html_footer');
	}
	
	public function privacy()
	{
		$data = array(
			'url' => 'blank',
			'active' => 'privacy'
		);
		$this->load->view('common/html_header', $this->head);
		$this->load->view('common/header-site', $data);
		$this->load->view('site/privacy');
		$this->load->view('common/footer-site');
		$this->load->view('common/html_footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */