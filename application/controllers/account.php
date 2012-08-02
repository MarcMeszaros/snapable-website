<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('account_model','',TRUE);  		    	
	}
	
	public function index()
	{
		redirect('/account/signin');
	}
	
	public function signin()
	{
		$segments = $this->uri->total_segments();
		
		if ( $segments == 3 && $this->uri->segment(3) == "error" )
		{
			$error = true;
		} else {
			$error = false;
		}
		
    	echo "&nbsp;";  
		$data = array(
			'css' => base64_encode('assets/css/setup.css,assets/css/signin.css'),
			'js' => base64_encode('assets/js/signin.js'),
			'error' => $error
		);
		$this->load->view('account/signin', $data);
	}
	
	public function validate()
	{
		if ( isset($_POST) )
		{
			// check if email is registered
			$userDeets = json_decode($this->account_model->userDetails($_POST['email']));
			if ( $userDeets->status == 200 )
			{
				// create password hash				
				$pbHash = base64_encode($this->account_model->pbkdf2('sha256', $_POST['password'], $userDeets->password_salt, $userDeets->password_iterations, 32, true));
				// check if password matches
				$validate = json_decode($this->account_model->checkPassword($_POST['email'], $pbHash));
				
				if ( $validate->status == 200 )
				{
					// set sessions var to log user in
					$sess_array = array(
			          'email' => $validate->email,
			          'fname' => $validate->fname,
			          'lname' => $validate->lname,
			          'resource_uri' => $validate->resource_uri,
			          'loggedin' => true
			        );
			        $this->session->set_userdata('logged_in', $sess_array);
					// send to dashboard
					redirect("/account/dashboard");
				} else {
					redirect("/account/signin/error");
				}
			} else {
				redirect("/account/signin/error");
			}
		} else {
			show_404();
		}
	}
	
	public function dashboard()
	{
		if($this->session->userdata('logged_in'))
		{
			$data = array(
				'session' => $this->session->userdata('logged_in'), 
				'css' => base64_encode('assets/css/setup.css,assets/css/dashboard.css'),
				'js' => base64_encode('assets/js/dashboard.js'),
				'url' => 'bigger-awesomer-event'
			);
			$this->load->view('account/dashboard', $data);
		} else {
			redirect("/account/signin");
		}
	}
	
	function signout()
	{
		$this->session->unset_userdata('logged_in');
		//session_destroy();
		redirect('/account/dashboard', 'refresh');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */