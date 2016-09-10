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
		require_http();
		$head = array(
			'keywords' => array('event', 'photo', 'photography', 'disposable', 'camera', 'share', 'sharing', 'day', 'big'),
			'description' => 'The easiest way to capture every moment at your event.',
			'css' => array(
				'assets/home/jan2013.css',
				'assets/home/overlays.css',
				'assets/home/colorbox.css',
				'assets/css/home_footer.css'
			),
			'js' => array(
				'assets/home/jquery.anchor.js',
				'assets/home/jquery.colorbox-min.js',
				'assets/home/jan2013.js'
			),
			'meta' => array(
				'og:title' => 'Snapable',
				'og:type' => 'Photo sharing',
				'og:url' => 'http://snapable.com',
				'og:image' => 'http://snapable.com/assets/img/snapable_logo.png',
				'og:description' => 'The easiest way to capture every moment at your event.',
				'og:site_name' => 'Snapable',
			),
		);

		// set utm/affiliate cookie if required
		if (isset($_GET['utm_source']) && isset($_GET['utm_medium']) && $_GET['utm_medium'] == 'affiliate') {
			$domain = (DEBUG) ? $_SERVER['HTTP_HOST'] : '.snapable.com';
			//$secure = (DEBUG) ? false : true;
			$cookie = array(
				'name'   => 'affiliate',
				'value'  => $_GET['utm_source'],
				'expire' => '2592000', // 30 days
				'domain' => $domain,
				'path'   => '/',
				//'secure' => $secure,
			);
			$this->input->set_cookie($cookie);
		}

		$this->load->view('common/html_header', $head);
		$this->load->view('home/jan2013', $head);
		$this->load->view('common/home_footer.php');
		$this->load->view('common/html_footer', $head);
	}

  public function goodbye()
  {
    require_http();
    $head = array(
      'keywords' => array('event', 'photo', 'photography', 'disposable', 'camera', 'share', 'sharing', 'day', 'big'),
      'description' => 'The easiest way to capture every moment at your event.',
      'css' => array(
        'assets/home/goodbye.css',
        'assets/home/colorbox.css',
        'assets/css/home_footer.css'
      ),
      'js' => array(
        'assets/home/jquery.anchor.js',
        'assets/home/jquery.colorbox-min.js',
        'assets/home/goodbye.js'
      ),
    );

    $this->load->view('common/html_header', $head);
    $this->load->view('home/goodbye', $head);
    $this->load->view('common/html_footer', $head);
  }


	function app()
	{
		$this->load->view('home/app');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
