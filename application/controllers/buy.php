<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buy extends CI_Controller {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('buy_model','',TRUE);
    	$this->load->model('account_model','',TRUE);

    	$this->load->library('email');
    	$this->load->helper('stripe');
    	$this->load->helper('currency');
	}

	/**
	 * Use the _remap to override the path logic.
	 * If the method exists in the controller, it takes precedence
	 * over a package name. If the controller function doesn't exist,
	 * assume it's the package that should be purchased and pass all
	 * following parameters in as well.
	 *
	 * NOTE: This means package short names MUST be different than any
	 * function name in this controller for the path to be resolvable.
	 */
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
		require_https(); // make sure we are using SSL

		// make sure we are logged in
		if(!SnapAuth::is_logged_in()) {
			redirect('/account/signin?redirect=/buy/'.$package);
		}

		// get the package data
		$data = array(
			'package' => json_decode($this->buy_model->getPackageDetails($package))
		);
		
		if( $data['package']->status == 200 )
		{
			$head = array(
				'stripe' => true,
				'ext_css' => array(
					'//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/cupertino/jquery-ui.css',
				),
				'css' => array(
					'assets/css/timePicker.css',
					'assets/css/setup.css',
					'assets/css/header.css',
					'assets/css/buy.css',
					'assets/css/footer-short.css'
				),
				'ext_js' => array(
					'//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js',
				),
				'js' => array(
					'assets/js/libs/jquery.timePicker.min.js',
					'assets/js/buy.js'
				),
			);

			// set the package id
			$packageParts = explode('/', $data['package']->resource_uri);
			$this->session->set_flashdata('package_id', $packageParts[3]);
			
			$this->load->view('common/html_header', $head);
			$this->load->view('common/header', array('linkHome' => true, 'url' => 'blank'));
			$this->load->view('buy/index', $data);
			$this->load->view('common/footer');
			$this->load->view('common/html_footer');
		} else {
			show_404();
		}
	}

	public function complete() {
		$session_data = SnapAuth::is_logged_in();
		if ( isset($_POST['stripeToken']) && $session_data)
		{	
			// get user/account details from session data set during signup
			$userParts = explode('/', $session_data['resource_uri']);
			$accountParts = explode('/', $session_data['account_uri']);

			// get package details
			$verb = 'GET';
			$path = 'package/'.$this->session->flashdata('package_id');
			$resp = SnapApi::send($verb, $path);
			$package = json_decode($resp['response']);

			// get the credit card details submitted by the form
			$token = $_POST['stripeToken'];

			try {
				// create the charge on Stripe's servers - this will charge the user's card
				$charge = Stripe_Charge::create(array(
				  'amount' => $package->price, // amount in cents, again
				  'currency' => 'usd',
				  'card' => $token,
				  'description' => $session_data['email'],
				));
				$chargeData = json_decode($charge);
			
				// create a Snapable order using the API
				$verb = 'POST';
				$path = 'order';
				$params = array(
					'total_price' => $package->price,
					'account' => $session_data['account_uri'],
					'user' => $session_data['resource_uri'],
					'paid' => $chargeData->paid,
					'items' => array(
						'package' => $this->session->flashdata('package_id'), // the package id
						'account_addons' => array(), // required field, but empty
						'event_addons' => array(), // required field, but empty
					),
					'payment_gateway_invoice_id' => $chargeData->id,
				);
				$resp = SnapApi::send($verb, $path, $params);

				// send email to user regardless of what happens after
				// ie. they should know we managed to charge their credit card,
				// even if stuff breaks after here
				$items = array(
					$package->name.' (package)' => array(
						'price' => $package->price,
					),
				);
				$receipt = array(
					'total' => $package->price,
					'items' => $items,
				);

				$this->email->initialize(array('mailtype'=>'html'));
				$this->email->from('team@snapable.com', 'Snapable');
				$this->email->to($session_data['email']);
				$this->email->subject('Your Snapable order has been processed');
				$this->email->message($this->load->view('email/receipt_html', $receipt, true));
				$this->email->set_alt_message($this->load->view('email/receipt_text', $receipt, true));
				$this->email->send();

				// if it makes the account invalid
				$validUntil = null;
				if (isset($package->interval) && isset($package->interval_count) && isset($package->trial_period_days))
				{
					// get the current datetime
					$dt = new DateTime('now', new DateTimeZone('UTC'));
					// make the trial time interval
					$intervalTrial = new DateInterval('P'.$package->trial_period_days.'D');
					
					// create the package interval based on package info
					$intervalStr = 'P'.$package->interval_count;
					if ($package->interval == 'day') {
						$intervalStr .= 'D';
					} else if ($package->interval == 'month') {
						$intervalStr .= 'M';
					} else if ($package->interval == 'year') {
						$intervalStr .= 'Y';
					}
					$intervalPackage = new DateInterval($intervalStr);

					// figure out when this all ends in the future by adding the two intervals
					$dt->add($intervalTrial);
					$dt->add($intervalPackage);
					$validUntil = $dt->format('c');
				}

				// update the account's package
				$verb = 'PUT';
				$path = 'account/'.$accountParts[3];
				$params = array(
					'package' => $package->resource_uri,
					'valid_until' => $validUntil,
				);
				$resp = SnapApi::send($verb, $path, $params);

			} catch (Stripe_CardError $e) {
				// keep the flash data if the user goes back
				$this->session->keep_flashdata('package_id');
				$this->session->keep_flashdata('package_price');
				show_error('Unable to process payment.<br>'.$e->getMessage(), 500);
			} catch (Exception $e) {
				// keep the flash data if the user goes back
				$this->session->keep_flashdata('package_id');
				$this->session->keep_flashdata('package_price');
				// send the exception to sentry
				$raven_client = new Raven_Client(SENTRY_DSN);
				$raven_client->captureException($e);
				show_error('Unable to process payment.<br>We\'ve been notified and are looking into it the problem.', 500);
			}
			
			$event_array = $this->account_model->eventDeets($session_data['account_uri']);
			$this->session->set_userdata('event_deets', $event_array);
			// redirect to the event
			redirect('/event/'.$event_array['url']);
		} else {
			$raven_client = new Raven_Client(SENTRY_DSN);
			$raven_client->captureMessage('Unable to process payment. There was no StripeToken or no user session.');
			show_error('Unable to process payment.<br>We\'ve been notified and are looking into it the problem.', 500);
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */