<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller {

	function __construct()
	{
    	parent::__construct(); 
    	$this->load->library('email');
    	$this->load->model('signup_model','',TRUE);		    	
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
		require_https();

		// use the "userdata" session because the flashdata is having problems...
		if (isset($package)) {
			$this->session->set_userdata('signup_package', $package);
		} else {
			// TODO: figure out a more elegant way than hardcoding the package name here as fallback
			$this->session->set_userdata('signup_package', 'standard');
		}
		
		$head = array(
			'linkHome' => true,
			'ext_css' => array(
				'//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/cupertino/jquery-ui.css',
			),
			'css' => array(
				'assets/css/timePicker.css',
				'assets/css/setup.css',
				'assets/css/header.css',
				'assets/css/signup.css'
			),
			'ext_js' => array(
				'//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js',
			),
			'js' => array(
				'assets/js/libs/jquery.timePicker.min.js',
				'assets/js/signup.js'
			),
			'url' => 'blank'	
		);
		$this->load->view('common/html_header', $head);
		$this->load->view('signup/index', $head);
		$this->load->view('common/html_footer');
	}
	
	
	function complete()
	{
		// get the package from the session and remove the data from the session
		$package = $this->session->userdata('signup_package');
		$this->session->unset_userdata('signup_package');
		$create_event = $this->signup_model->createEvent($_POST['event'], $_POST['user']);
		
		if ( $create_event !== false )
		{
			// set sessions var to log user in
			$sess_array = array(
	          'email' => $_POST['user']['email'],
	          'fname' => $_POST['user']['first_name'],
	          'lname' => $_POST['user']['last_name'],
	          'resource_uri' => $create_event['user'],
	          'account_uri' => $create_event['account'],
	          'loggedin' => true
	        );
	        $this->session->set_userdata('logged_in', $sess_array);
	        
	        // redirect to the package buying part
	        redirect('/buy/'.$package); 
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