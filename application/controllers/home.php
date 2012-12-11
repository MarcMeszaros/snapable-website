<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$head = array(
			'keywords' => array('wedding', 'photo', 'photography', 'disposable', 'camera', 'share', 'sharing', 'day', 'big'),
			'description' => 'The easiest way to capture every moment at your wedding.',
			'css' => array(
				'assets/home/snap.css', 
				'assets/home/overlays.css', 
				'assets/home/colorbox.css'
			),
			'js' => array('assets/home/jquery.anchor.js'),
			'meta' => array(
				'og:title' => 'Snapable',
				'og:type' => 'Photo sharing',
				'og:url' => 'http://snapable.com',
				'og:image' => 'http://snapable.com/assets/img/snapable_logo.png',
				'og:description' => 'The easiest way to capture every moment at your wedding.',
				'og:site_name' => 'Snapable',
			),
		);

		$this->load->view('common/html_header', $head);
		$this->load->view('home/december2012');
		$this->load->view('common/html_footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */