<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

	function __construct()
	{
    	parent::__construct();
    	$this->load->library('email');
    	$this->load->model('account_model','',TRUE);  		    	
	}
	
	public function index()
	{
		redirect('/account/signin');
	}
	
	public function signin()
	{
		require_https(); // make sure we are in ssl

		// check if we are already logged in, and redirect if we are
		$userLogin = SnapAuth::is_logged_in(); 
		if($userLogin) {
			$event_array = $this->account_model->eventDeets($userLogin['account_uri']);
			$this->session->set_userdata('event_deets', $event_array);

			redirect('/event/'.$event_array['url']);
		}
		
		$segments = $this->uri->total_segments();
		
		$error = ( $segments == 3 && $this->uri->segment(3) == "error" ) ? true:false;
		$reset = ( isset($_GET['reset']) ) ? true:false;

		// if there a redirect param, use it
		$redirect = $this->input->get('redirect');
		if($redirect) {
			$this->session->set_flashdata('redirect', $redirect);
		}
		
    	$data = array(
			'css' => array('assets/css/setup.css', 'assets/css/signin.css'),
			'js' => array('assets/js/signin.js'),
			'error' => $error,
			'reset' => $reset,
		);
		$this->load->view('common/html_header', $data);
		$this->load->view('account/signin', $data);
		$this->load->view('common/html_footer', $data);
	}
	
	public function validate()
	{
		if ( isset($_POST) )
		{
			// create password hash				
			$pbHash = SnapAuth::snap_hash($_POST['email'], $_POST['password']);
			// check if password matches
			$userLogin = SnapAuth::signin($_POST['email'], $pbHash);
			if ( $userLogin ) {
				// send to dashboard/redirect
				if($this->session->flashdata('redirect')){
					redirect($this->session->flashdata('redirect'));
				} else {
					$event_array = $this->account_model->eventDeets($userLogin['account_uri']);
					$this->session->set_userdata('event_deets', $event_array);

					redirect('/account/'.$event_array['url']);
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
		require_https();
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
					'css' => array('assets/css/setup.css', 'assets/css/dashboard.css'),
					'js' => array('assets/js/dashboard.js'),
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

			$this->email->from($from, 'Snapable');
			$this->email->to($to);
			$this->email->subject($subject);
			$this->email->message($message_html);
			$this->email->set_alt_message($message_text);		
			if ( $this->email->send() )
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
		SnapAuth::signout();
		redirect('/account/signin', 'refresh');
	}
	
	function reset($nonce = NULL)
	{
		
		$data = array(
			'css' => array('assets/css/setup.css', 'assets/css/signin.css')
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
						'css' => array('assets/css/setup.css', 'assets/css/signin.css')
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