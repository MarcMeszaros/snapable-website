<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buy extends CI_Controller {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('buy_model','',TRUE);

    	$this->load->helper('stripe');
	}

	public function _remap($method, $params = array())
	{
	    if (method_exists($this, $method)) {
	    	return call_user_func_array(array($this, $method), $params);
	    } else {
	    	array_unshift($params, $method); // add the method as the first param
	    	return call_user_func_array(array($this, 'index'), $params);
	    }
	}
	 
	public function index($package=null)
	{
		$data = array(
			'package' => json_decode($this->buy_model->getPackageDetails($package))
		);
		
		if( $data['package']->status == 404 )
		{
			show_404();
		} else {
			$head = array(
				'linkHome' => true,
				'css' => base64_encode('assets/css/cupertino/jquery-ui-1.8.21.custom.css,assets/css/timePicker.css,assets/css/setup.css,assets/css/header.css,assets/css/buy.css,assets/css/footer-short.css'),
				'js' => base64_encode('assets/js/jquery-ui-1.8.21.custom.min.js,assets/js/jquery.timePicker.min.js,assets/js/buy.js'),
				'url' => 'blank',
			);
			$this->load->view('common/html_header', $head);
			$this->load->view('common/header', $head);
			$this->load->view('buy/index', $data);
			$this->load->view('common/footer');
			$this->load->view('common/html_footer');
		}
	}

	public function complete() {
		if ( isset($_POST['cc']) && isset($_POST['address']) )
		{
			$data = array(
				//'title' => $_POST['event']['title'],
				'css' => base64_encode('assets/css/loader.css'),
			);
			/*
			$myCard = array('number' => '4242424242424242', 'exp_month' => 5, 'exp_year' => 2015);
			$charge = Stripe_Charge::create(array('card' => $myCard, 'amount' => 2000, 'currency' => 'usd'));
			echo $charge;
			*/

			$this->load->view('common/html_header', $data);
			$this->load->view('buy/complete', $data);
			$this->load->view('common/html_footer', $data);
			
		} else {
			show_404();
		}
	}

	public function error() {
		echo "A problem has occurred.";
	}

	public function check() {
		if ( isset($_GET['email']) )
		{
			$is_registered = $this->buy_model->checkEmail($_GET['email']);
		}
		else if ( isset($_GET['url']) )
		{
			$is_registered = $this->buy_model->checkUrl($_GET['url']);
		} else {
			$is_registered = '{ "status": 404 }';
		}
		echo $is_registered;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */