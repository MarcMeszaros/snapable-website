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
					// get users events
					// http://devapi.snapable.com/private_v1/event/?format=json&user=1
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
			// get event details
			$session_data = $this->session->userdata('logged_in');
			
			$event_array = $this->account_model->eventDeets($session_data['resource_uri']);
			$this->session->set_userdata('event_deets', $event_array);
			
			if ( $event_array['status'] == 200 )
			{
				$days_until_epoch = $event_array['start_epoch'] - time();
				$days_until = round($days_until_epoch / 86400);
				
				if( $days_until < 0 )
				{
					$days_until = substr($days_until, 1);
					$days_verb = "Since";
				} else {
					$days_verb = "Until";
				}
				
				$data = array(
					'session' => $this->session->userdata('logged_in'), 
					'css' => base64_encode('assets/css/setup.css,assets/css/dashboard.css'),
					'js' => base64_encode('assets/js/dashboard.js'),
					'eventDeets' => $event_array,
					'days_until' => $days_until,
					'days_verb' => $days_verb
				);
				$this->load->view('account/dashboard', $data);
			} else {
				echo "INSERT MESSAGE THAT NO EVENT WAS FOUND FOR THIS PERSON.";
			}
		} else {
			redirect("/account/signin");
		}
	}
	
	function email()
	{
		if ( IS_AJAX && isset($_POST['type']) && isset($_POST['message']) )
		{
			$url = 'http://sendgrid.com/';
			$user = 'snapable';
			$pass = 'Snapa!23'; 
			
			if ( isset($_POST['to']) ) {
				$to = $_POST['to'];
			} else {
				$to = "team@snapable.com";
			}
			
			if ( isset($_POST['from']) ) {
				$from = $_POST['from'];
			} else {
				$from = "website@snapable.com";
			}
			
			if ( $_POST['type'] == "question" )
			{
				$subject = 'Message From Customer';
				$message_html = '<p><b>Message:</b></p><p>' . $_POST['message'] . '</p><p>Sent from: ' . $_POST['email'] . '</p>';
				$message_text = 'Message: ' . $_POST['message'] . ' / Sent from: ' . $_POST['email'];
			} else {
				$subject = 'Follow my event @ Snapable!';
				$message_html = $_POST['message'];
				$message_text = $_POST['message'];
			}
			
			$params = array(
			    'api_user'  => $user,
			    'api_key'   => $pass,
			    'to'        => $to,
			    'subject'   => $subject,
			    'html'      => $message_html,
			    'text'      => $message_text,
			    'from'      => $from,
			  );
			
			$request =  $url.'api/mail.send.json';
			
			// Generate curl request
			$session = curl_init($request);
			// Tell curl to use HTTP POST
			curl_setopt ($session, CURLOPT_POST, true);
			// Tell curl that this is the body of the POST
			curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
			// Tell curl not to return headers, but do return the response
			curl_setopt($session, CURLOPT_HEADER, false);
			curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
			
			// obtain response
			$response = json_decode(curl_exec($session));
			curl_close($session);
			
			if ( $response->message == "success" )
			{
				echo "sent";
			} else {
				echo "failed";
			}
		} else {
			show_404();
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