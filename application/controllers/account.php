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
		if( (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "on") && $_SERVER['HTTP_HOST'] != "snapable")
		{
		    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		    exit();
		}
		
		$segments = $this->uri->total_segments();
		
		$error = ( $segments == 3 && $this->uri->segment(3) == "error" ) ? true:false;
		$reset = ( isset($_GET['reset']) ) ? true:false;
		
    	$data = array(
			'css' => base64_encode('assets/css/setup.css,assets/css/signin.css'),
			'js' => base64_encode('assets/js/signin.js'),
			'error' => $error,
			'reset' => $reset
		);
		$this->load->view('common/html_header', $data);
		$this->load->view('account/signin', $data);
		$this->load->view('common/html_footer', $data);
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
			          'account_uri' => $validate->account_uri,
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
			
			$event_array = $this->account_model->eventDeets($session_data['account_uri']);
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
				$this->load->view('common/html_header', $data);
				$this->load->view('account/dashboard', $data);
				$this->load->view('common/html_footer', $data);
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
			
			$to = ( isset($_POST['to']) ) ? $_POST['to']:"team@snapable.com";
			$from = ( isset($_POST['from']) ) ? $_POST['from']:"website@snapable.com";
			
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
	
	function reset($nonce = NULL)
	{
		
		$data = array(
			'css' => base64_encode('assets/css/setup.css,assets/css/signin.css')
		);
		
		if ( $nonce == NULL )
		{
			if ( isset($_GET['error']) )
			{
				$data['error'] = "<div id='error'>We weren't able to reset your password<br />Please try again.</div>";
			} else {
				$data['error'] = "";
			}
			$this->load->view('common/html_header', $data);
			$this->load->view('account/reset', $data);
			$this->load->view('common/html_footer', $data);
		} else {
			$data['nonce'] = $nonce;
			$this->load->view('common/html_header', $data);
			$this->load->view('account/new_password', $data);
			$this->load->view('common/html_footer', $data);
		}
	}
	
	function doreset()
	{
		if ( isset($_POST) && isset($_POST['email']) )
		{
			$userDeets = json_decode($this->account_model->userDetails($_POST['email']));
			
			if ( $userDeets->status == 200 )
			{
				$resource_uri = explode("/", $userDeets->resource_uri);
				$nonce = json_decode($this->account_model->doReset($resource_uri[3]));
				
				if ( $nonce = 1 )
				{
					$data = array(
						'css' => base64_encode('assets/css/setup.css,assets/css/signin.css')
					);
					$this->load->view('common/html_header', $data);
					$this->load->view('account/email_sent', $data);
					$this->load->view('common/html_footer', $data);
				} else {
					redirect("/account/reset?error");
				}
			} else {
				echo '{
					"status": 404
				}';
			}
		} else {
			echo '{
				"status": 404
			}';
		}
	}
	
	function password()
	{
		if ( isset($_POST['password']) && isset($_POST['nonce']) )
		{
			$reset = $this->account_model->completeReset($_POST['password'], $_POST['nonce']); 
			
			if ( $reset == 0 )
			{
				redirect("/account/reset/?error");
			} else {
				redirect("/account/signin?reset");
			}
		} else {
			show_404();
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */