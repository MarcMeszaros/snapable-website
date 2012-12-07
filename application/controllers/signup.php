<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller {

	function __construct()
	{
    	parent::__construct(); 
    	$this->load->model('signup_model','',TRUE);		    	
	}
	
	public function index()
	{
		if( (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "on") && $_SERVER['HTTP_HOST'] != "snapable")
		{
		    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		    exit();
		}
		
		$head = array(
			'linkHome' => true,
			'css' => base64_encode('assets/css/cupertino/jquery-ui-1.8.21.custom.css,assets/css/timePicker.css,assets/css/setup.css,assets/css/header.css,assets/css/signup.css'),
			'js' => base64_encode('assets/js/jquery-ui-1.8.21.custom.min.js,assets/js/jquery.timePicker.min.js,assets/js/signup.js'),
			'url' => 'blank'	
		);
		$this->load->view('common/html_header', $head);
		$this->load->view('signup/index', $head);
		$this->load->view('common/html_footer', $head);
	}
	
	
	function complete()
	{
		$data = array(
			'title' => $_POST['event']['title'],
			'css' => base64_encode('assets/css/loader.css'),
		);
		$this->load->view('common/html_header', $data);
		$this->load->view('signup/complete', $data);
		$this->load->view('common/html_footer', $data);
		
		$create_event = $this->signup_model->createEvent($_POST['event'], $_POST['user']);
		
		if ( $create_event !== false )
		{
			// set sessions var to log user in
			$sess_array = array(
	          'email' => $_POST['user']['email'],
	          'fname' => $_POST['user']['first_name'],
	          'lname' => $_POST['user']['last_name'],
	          'user_uri' => $create_event['user'],
	          'account_uri' => $create_event['account'],
	          'resource_uri' => $create_event['event'],
	          'loggedin' => true
	        );
	        $this->session->set_userdata('logged_in', $sess_array);
			redirect('/buy/standard'); 
		} else {
			show_error('Unable to create event.', 500);
		}
	}
	
	
	function check()
	{
		if ( isset($_GET['email']) )
		{
			$is_registered = $this->signup_model->checkEmail($_GET['email']);
		}
		else if ( isset($_GET['url']) )
		{
			$is_registered = $this->signup_model->checkUrl($_GET['url']);
		} else {
			$is_registered = '{ "status": 404 }';
		}
		echo $is_registered;
	}
	
}