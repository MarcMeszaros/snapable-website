<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller {

	// coupon codes key/value pairs (in cents)
	// NOTE: coupon codes need to be lowercase!
	// (ie. case insensitive input, but all lowercase behind the scenes)
	public static $COUPON_CODES = array(
		'201bride' => 1000, // added: 2013-03-26; valid_until: TBD
		'adorii' => 7900, // added: 2013-01-24; valid_until: TBD
		'adorii5986' => 7900, // added: 2013-02-06; valid_until: TBD
		'bespoke' => 1000, // added: 2013-01-31; valid_until: TBD
		'betheman' => 1000, // added: 2013-01-31; valid_until: TBD
		'bridaldetective' => 1000, // added: 2013-01-31; valid_until: TBD
		'budgetsavvy' => 1000, // added: 2013-02-26; valid_until: TBD
		'enfianced' => 1000, // added: 2013-01-31; valid_until: TBD
		'gbg' => 1000, // added: 2013-01-31; valid_until: TBD
		'poptastic' => 1000, // added: 2013-01-31; valid_until: TBD
		'smartbride' => 1000, // added: 2013-01-31; valid_until: TBD
		'snaptrial2013' => 7900, // added: 2013-03-14; valid_until: TBD
		'weddingful5986' => 7900, // added: 2013-02-06; valid_until: TBD
		'wr2013' => 1000, // added: 2013-01-17; valid_until: TBD
	);

	function __construct()
	{
    	parent::__construct(); 
    	$this->load->library('email');
    	$this->load->model('signup_model','',TRUE);		
    	$this->load->model('account_model','',TRUE);
		
    	$this->load->library('email');
    	$this->load->helper('stripe');
    	$this->load->helper('currency');
    	$this->load->helper('cookie');  	
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
		// get package details
		$verb = 'GET';
		$path = 'package/2'; // standard package
		$resp = SnapApi::send($verb, $path);
		$package = json_decode($resp['response']);

		// set price in cents
		$amount_in_cents = $package->price;

		// if there is a promo code to process
		if (isset($_POST) && $_POST['promo-code-applied'] == 1 && isset($_POST['promo-code']))
		{
			// sanitize the data (ie. remove invalid characters and lowercase)
			$code = strtolower(preg_replace('/[^a-zA-Z0-9-_]/', '', $_POST['promo-code']));

			// only apply discount if coupon is valid
			if (array_key_exists($code, self::$COUPON_CODES)) {
				$discount = self::$COUPON_CODES[$code];
				$amount_in_cents = $amount_in_cents - $discount;
				$coupon = $code;
			}
		}

		// USED BY /signup as of Jan 4, 2013
		if ( isset($_POST) && ($amount_in_cents == 0 || ($amount_in_cents > 0 && isset($_POST['stripeToken']))) )
		{
			// Step 1: Setup account/user and log them in	
			$create_event = $this->signup_model->createEvent($_POST['event'], $_POST['user']);
			
			if ( $create_event )
			{
				// set sessions var to log user in
				//SnapAuth::signin_nohash($_POST['user']['email']);
				$hash = SnapAuth::snap_hash($_POST['user']['email'], $_POST['user']['password']);
				SnapAuth::signin($_POST['user']['email'], $hash);
		        
		        // Step 2: Bill'em Dano
		    	$session_data = SnapAuth::is_logged_in();
								
				if ( $session_data )
				{	
					// get user/account details from session data set during signup
					$userParts = explode('/', $session_data['resource_uri']);
					$accountParts = explode('/', $session_data['account_uri']);
					
					try {
						if ($amount_in_cents > 0) {
							// get the credit card details submitted by the form
							$token = $_POST['stripeToken'];

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
								'total_price' => $amount_in_cents,
								'account' => $session_data['account_uri'],
								'user' => $session_data['resource_uri'],
								'paid' => $chargeData->paid,
								'items' => array(
									'package' => 2, // the package id
									'account_addons' => array(), // required field, but empty
									'event_addons' => array(), // required field, but empty
								),
								'payment_gateway_invoice_id' => $chargeData->id,
							);
							// add the coupon if there was one
							if (isset($coupon)) {
								$params['coupon'] = $coupon;
							}
							$resp = SnapApi::send($verb, $path, $params);

							// get the orderID
							if(isset($resp)) {
								$response = json_decode($resp['response']);
								$idParts = explode('/', $response->resource_uri);
								$orderID = $idParts[3];
								$this->session->set_flashdata('orderID', $orderID);
							}
						}

						// disable the subscribe link sendgrid automatically adds
						$email_headers = array(
							'filters' => array(
								'subscriptiontrack' => array(
									'settings' => array(
										'enable' => 0,
									),
								),
							),
						);

						// signup email
						//GET TIMEZONE
						$timezone_offset_seconds = $_POST['event']['tz_offset'] * 60;
						// SET TO UTC
						$start_timestamp = strtotime($_POST['event']['start_date'] . " " . $_POST['event']['start_time']) + ($timezone_offset_seconds);

						// CREATE END DATE
						if ( $_POST['event']['duration_type'] == "days" )
						{
							$duration_in_seconds = $_POST['event']['duration_num'] * 86400;
						} else {
							$duration_in_seconds = $_POST['event']['duration_num'] * 3600;
						}
						$end_timestamp = $start_timestamp + $duration_in_seconds;

						$signup_details = array(
							'start_timestamp' => $start_timestamp,
							'end_timestamp' => $end_timestamp,
							'email_address' => $_POST['user']['email'],
							'affiliate' => '',
							'total' => $amount_in_cents,
						);
						$signup_details['coupon'] = (isset($coupon)) ? $coupon : '';
						if ($this->input->cookie('affiliate')) {
							$signup_details['affiliate'] = $this->input->cookie('affiliate');

							// delete the cookie
							delete_cookie('affiliate');
						}

						// SEND SIGN-UP NOTIFICATION EMAIL
						$subject = 'Say Cheese, a Snapable Sign-up!';

						$this->email->initialize(array('mailtype'=>'html'));
						$this->email->set_header('X-SMTPAPI', json_encode($email_headers));
						$this->email->from('robot@snapable.com', 'Snapable');
						$this->email->to('team@snapable.com');
						$this->email->subject($subject);
						$this->email->message($this->load->view('email/user_signup_html', $signup_details, true));
						$this->email->set_alt_message($this->load->view('email/user_signup_txt', $signup_details, true));		
						if (DEBUG == false) {
							$this->email->send();
						}

						// send email to user regardless of what happens after
						// ie. they should know we managed to charge their credit card,
						// even if stuff breaks after here
						$items = array(
							'Snapable Event' => array(
								'price' => $amount_in_cents,
							),
						);
						$receipt = array(
							'total' => $amount_in_cents,
							'items' => $items,
						);

					    // send the receipt email
						$this->email->initialize(array('mailtype'=>'html'));
						$this->email->set_header('X-SMTPAPI', json_encode($email_headers));
						$this->email->from('support@snapable.com', 'Snapable');
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
						//$this->session->keep_flashdata('package_id');
						//$this->session->keep_flashdata('package_price');
						show_error('Unable to process payment.<br>'.$e->getMessage(), 500);
					} catch (Exception $e) {
						// keep the flash data if the user goes back
						//$this->session->keep_flashdata('package_id');
						//$this->session->keep_flashdata('package_price');
						// send the exception to sentry
						$raven_client = new Raven_Client(SENTRY_DSN);
						$raven_client->captureException($e);
						show_error('Unable to process payment.<br>We\'ve been notified and are looking into the problem.', 500);
					}
					
					$event_array = $this->account_model->eventDeets($session_data['account_uri']);
					$this->session->set_userdata('event_deets', $event_array);
					// redirect to the event
					//redirect('/event/'.$event_array['url']);
					// redirect to thank you page
					$this->session->set_flashdata('event', $event_array['url']);
					$this->session->set_flashdata('amount', $amount_in_cents);
					redirect('/signup/complete');
				} else {
					$raven_client = new Raven_Client(SENTRY_DSN);
					$raven_client->captureMessage('Unable to process payment. There was no StripeToken or no user session.');
					show_error('Unable to process payment.<br>We\'ve been notified and are looking into the problem.', 500);
					// redirect to form and display error
				}
			} else {
				$raven_client = new Raven_Client(SENTRY_DSN);
				$raven_client->captureMessage('Unable to create event. There was no valid response after creating the event..');
				show_error('Unable to create the event.<br>We\'ve been notified and are looking into the problem.', 500);
			}
		} else {
			show_404();
		}
	}
	
	
	function complete()
	{
		// get the data from the flash session
		$event_url = $this->session->flashdata('event');
		$orderID = $this->session->flashdata('orderID');
		$amount = $this->session->flashdata('amount');

		// put the event_url in the data to pass to the view
		$data = array();
		$data['event_url'] = (isset($event_url)) ? $event_url : '';

		// put the share a sale stuff
		if (isset($orderID) && isset($amount)) {
			$amount_sale = currency_cents_to_dollars($amount);
			$url = 'https://shareasale.com/sale.cfm?amount='.$amount_sale.'&tracking='.$orderID.'&transtype=sale&merchantID=43776';
			$data['url'] = $url;
		}

		$head = array(
            'css' => array('assets/css/loader.css'),
        );

		// load up the view
		$this->load->view('common/html_header', $head);
		$this->load->view('signup/complete', $data);
		$this->load->view('common/html_footer');
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
		$numargs = func_num_args();

		if ( IS_AJAX && isset($_GET['code']) && ($numargs == 0 || $numargs == 1) )
		{
			// sanitize the data (ie. remove invalid characters and lowercase)
			$code = strtolower(preg_replace('/[^a-zA-Z0-9-_]/', '', $_GET['code']));
			
			if ( array_key_exists($code, self::$COUPON_CODES) )
			{
			    // success
			    echo '{
			    	"status": 200,
			    	"value": ' . self::$COUPON_CODES[$code]/100 . '
			    }';
			} else {
			    echo '{
			    	"status": 404
			    }';
			}
		} else {
			return 0;
		}		
	}
	
}