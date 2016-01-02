<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

	function __construct() {
    	parent::__construct();
    	$this->load->library('email');
    	$this->load->model('account_model','',TRUE);
	}

	public function index() {
		redirect('/account/signin', 'refresh');
	}

	public function signin() {
		require_https(); // make sure we are in ssl

		// check if we are already logged in, and redirect if we are
		$userLogin = SnapAuth::is_logged_in();
		if ($userLogin) {
			$event_array = $this->account_model->eventDeets($userLogin['account_uri']);
			$this->session->set_userdata('event_deets', $event_array);

			redirect('/event/'.$event_array['url']);
		}

		$segments = $this->uri->total_segments();
		$error = ( $segments >= 3 && $this->uri->segment(3) == "error" ) ? true : false;
		$reset = ( isset($_GET['reset']) ) ? true : false;

		// if there a redirect param, use it
		$redirect = $this->input->get('redirect');
		if ($redirect) {
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

	public function validate() {
		if ( isset($_POST) ) {
			// create password hash
			$pbHash = SnapAuth::snap_hash($_POST['email'], $_POST['password']);
			// check if password matches
			$userLogin = SnapAuth::signin($_POST['email'], $pbHash);
			if ( $userLogin ) {
				$event_array = $this->account_model->eventDeets($userLogin['account_uri']);
				$this->session->set_userdata('event_deets', $event_array);

				redirect('/event/'.$event_array['url']);
			} else {
				redirect('/account/signin/error');
			}
		} else {
			show_404();
		}
	}

	function signout() {
		SnapAuth::signout();
		redirect('/account/signin', 'refresh');
	}

	function reset($nonce = NULL) {
		require_https();
		$head = array(
			'css' => array('assets/css/setup.css', 'assets/css/signin.css'),
		);

		if ( isset($_POST['password']) && isset($_POST['nonce']) ) {
			$verb = 'PATCH';
			$path = '/user/passwordreset/';
			$params = array(
				'nonce' => $_POST['nonce'],
				'password' => $_POST['password'],
			);
			$reset = SnapApi::send($verb, $path, $params);

			if ( $reset['code'] == 202 ) {
				redirect("/account/signin?reset");
			} else {
				redirect("/account/reset/?error");
			}
		} else if (empty($nonce)) {
			$this->load->view('common/html_header', $head);
			$this->load->view('account/reset');
			$this->load->view('common/html_footer');
		} else {
			$data = array();
			if (isset($nonce) && strlen($nonce) >= 64) {
				$data['nonce'] = $nonce;
			} else if (isset($nonce) && strlen($nonce) < 64) {
				$data['nonce'] = '';
				$data['error'] = "The reset link seems to be invalid.";
			}

			$this->load->view('common/html_header', $head);
			$this->load->view('account/new_password', $data);
			$this->load->view('common/html_footer');
		}

	}

	function doreset() {
		$data = array(
			'css' => array('assets/css/setup.css', 'assets/css/signin.css')
		);
		if ( isset($_POST) && isset($_POST['email']) ) {
			$userDeets = json_decode($this->account_model->userDetails($_POST['email']));

			if ( $userDeets->status == 200 ) {
				$resp = $this->account_model->doReset(SnapApi::resource_pk($userDeets->resource_uri));

				if ( $resp['code'] == 201 ) {
					$this->load->view('common/html_header', $data);
					$this->load->view('account/email_sent', $data);
					$this->load->view('common/html_footer', $data);
				} else {
					redirect("/account/reset?error");
				}
			} else {
				$data['error'] = '<div id="error">We weren\'t able to reset your password.<br />Please make sure your email address is correct.</div>';
				$this->load->view('common/html_header', $data);
				$this->load->view('account/reset', $data);
				$this->load->view('common/html_footer', $data);
			}
		} else {
			$data['error'] = '<div id="error">We weren\'t able to reset your password.<br />If the problem persists, please <a href="/site/contact">Contact Us</a>.</div>';
			$this->load->view('common/html_header', $data);
			$this->load->view('account/reset', $data);
			$this->load->view('common/html_footer', $data);
		}
	}

	function password()
	{
		if ( isset($_POST['password']) && isset($_POST['nonce']) )
		{
			$reset = $this->account_model->completeReset($_POST['password'], $_POST['nonce']);

			if ( $reset == 0 ) {
				redirect("/account/reset/?error");
			} else {
				redirect("/account/signin?reset");
			}
		} else {
			show_404();
		}
	}

	function dashboard()
	{
		$userLogin = SnapAuth::is_logged_in();
		$guestLogin = SnapAuth::is_guest_logged_in();
		if ($userLogin) {
			$verb = 'GET';
			$path = '/event/';
			$params = array(
				'account' => SnapApi::resource_pk($userLogin['account_uri']),
			);
			$resp = SnapApi::send($verb, $path, $params);
			$result = json_decode($resp['response']);
			$events = $result->objects;

			$head = array(
				'css' => array(
						'assets/css/header.css',
						'assets/css/footer.css',
						'assets/css/account/dashboard.css',
				),
				'navigation' => array(
						'full_name' => $userLogin['first_name'] . ' ' . $userLogin['last_name'],
						'session' => $userLogin,
				)
			);
			$data = array(
				'events' => $events,
			);

			// display the page
			$this->load->view('common/html_header', $head);
			$this->load->view('common/bootstrap_header', $head);
			$this->load->view('account/dashboard', $data);
			$this->load->view('common/bootstrap_footer');
			$this->load->view('common/html_footer');

		} else {
			redirect('/account/signin');
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
