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
			'stripe' => true,
			'linkHome' => true,
			'ext_css' => array(
				'//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/cupertino/jquery-ui.css',
			),
			'css' => array(
				'assets/css/timePicker.css',
				'assets/css/setup.css',
				'assets/css/signup_jan2013.css', 
				'assets/css/home_footer.css'
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
		$this->load->view('signup/signup-jan2013', $head);
		$this->load->view('common/home_footer.php');
		$this->load->view('common/html_footer');
	}
	
	
	function setup()
	{
		// USED BY /signup as of Jan 4, 2013
		
		if ( isset($_POST) && isset($_POST['stripeToken']) )
		{
			/*
			 echo "<pre>";
			 print_r($_POST);
			 echo "</pre>";
			 */
			 
			 // Step 1: Setup account/user and log them in
			
			$create_event = $this->signup_model->createEvent($_POST['event'], $_POST['user']);
			
			if ( $create_event )
			{
				// set sessions var to log user in
				//SnapAuth::signin_nohash($_POST['user']['email']);
				$hash = SnapAuth::snap_hash($_POST['user']['email'], $_POST['user']['password']);
				SnapAuth::signin($_POST['user']['email'], $hash);
		        
		        // Step 2: Bill'em Dano
		        
		        $this->load->model('account_model','',TRUE);
		
		    	$this->load->library('email');
		    	$this->load->helper('stripe');
		    	$this->load->helper('currency');
		    	
		    	$session_data = SnapAuth::is_logged_in();
				
				//echo "<pre>";
				//print_r($session_data);
				//echo "</pre>";
				
				
				if ( $session_data )
				{	
					// get user/account details from session data set during signup
					$userParts = explode('/', $session_data['resource_uri']);
					$accountParts = explode('/', $session_data['account_uri']);
					
					// set price in cents
					$amount_in_dollars = 79;
					$amount_in_cents = 7900;
					
					if ( $_POST['promo-code-applied'] == 1 )
					{
						$discount = $_POST['promo-code-amount'] * 100;
						$amount_in_cents = $amount_in_cents - $discount;
						$amount_in_dollars = $amount_in_dollars - $_POST['promo-code-amount'];
					}
					
					if ( $amount_in_cents > 0 )
					{
						// get the credit card details submitted by the form
						$token = $_POST['stripeToken'];
						
						try {
							// create the charge on Stripe's servers - this will charge the user's card
							$charge = Stripe_Charge::create(array(
							  'amount' => $amount_in_cents, // amount in cents, again
							  'currency' => 'usd',
							  'card' => $token,
							  'description' => "$" . ($amount_in_cents / 100) . " charge for Snapable event to " . $session_data['email'],
							));
							$chargeData = json_decode($charge);
							
							// create a Snapable order using the API
							$verb = 'POST';
							$path = 'order';
							$params = array(
								'total_price' => 79,
								'account' => $session_data['account_uri'],
								'user' => $session_data['resource_uri'],
								'paid' => $chargeData->paid,
								'items' => array(
									'package' => 0, // the package id
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
								'Snapable Event' => array(
									'price' => $amount_in_dollars,
								),
							);
							$receipt = array(
								'total' => $amount_in_cents,
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
					}
					
					$event_array = $this->account_model->eventDeets($session_data['account_uri']);
					$this->session->set_userdata('event_deets', $event_array);
					// redirect to the event
					redirect('/event/'.$event_array['url']);
				} else {
					$raven_client = new Raven_Client(SENTRY_DSN);
					$raven_client->captureMessage('Unable to process payment. There was no StripeToken or no user session.');
					show_error('Unable to process payment.<br>We\'ve been notified and are looking into it the problem.', 500);
					// redirect to form and display error
				}
		        
		        /*
		        
		
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
				
				*/
			} else {
				echo "Error: Unable to create event";	
			}
		} else {
			show_404();
		}
	}
	
	
	function complete()
	{
		// get the package from the session and remove the data from the session
		$package = $this->session->userdata('signup_package');
		$this->session->unset_userdata('signup_package');

		// check for bots
		foreach ($_POST['re-cap'] as $key => $value) {
			if ($value != '') {
				show_error('We think you are a spam bot...', 403);
			}
		}

		$create_event = $this->signup_model->createEvent($this->input->post('event'), $this->input->post('user'));

		if ( $create_event )
		{
			// set sessions var to log user in
			SnapAuth::signin_nohash($_POST['user']['email']);
	        
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
	
	function promo()
	{
		if ( IS_AJAX && isset($_GET['code']) )
		{
			$promo_codes = array(
				"test" => 5,
				"weddingful" => 10
			);
			
			if ( array_key_exists($_GET['code'], $promo_codes) ) 
			{
			    // success
			    echo '{
			    	"status": 200,
			    	"value": ' . $promo_codes[$_GET['code']] . '
			    }';
			} else {
			    echo '{
			    	"status": 404
			    }';
			}
		} else {
			show_404();	
		}		
	}
	
}