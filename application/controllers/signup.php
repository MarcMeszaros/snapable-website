<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller {

	// coupon codes key/value pairs (in cents)
	// NOTE: coupon codes need to be lowercase!
	// (ie. case insensitive input, but all lowercase behind the scenes)
	public static $COUPON_CODES = array(
		'201bride' => 1000, // added: 2013-03-26; valid_until: TBD
		'adorii' => 4900, // added: 2013-01-24; valid_until: TBD
		'adorii5986' => 4900, // added: 2013-02-06; valid_until: TBD
		'bespoke' => 1000, // added: 2013-01-31; valid_until: TBD
		'betheman' => 1000, // added: 2013-01-31; valid_until: TBD
		'bridaldetective' => 1000, // added: 2013-01-31; valid_until: TBD
		'budgetsavvy' => 1000, // added: 2013-02-26; valid_until: TBD
		'enfianced' => 1000, // added: 2013-01-31; valid_until: TBD
		'gbg' => 1000, // added: 2013-01-31; valid_until: TBD
		'poptastic' => 1000, // added: 2013-01-31; valid_until: TBD
		'smartbride' => 1000, // added: 2013-01-31; valid_until: TBD
		'snapdeal2013' => 4900, // added: 2013-03-26; valid_until: TBD
		'snaptrial2013' => 4900, // added: 2013-03-14; valid_until: TBD
		'weddingful5986' => 4900, // added: 2013-02-06; valid_until: TBD
		'wr2013' => 1000, // added: 2013-01-17; valid_until: TBD
	);

	public static $PACKAGE_ID = 3;

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

		// get package details
		$verb = 'GET';
		$path = 'package/'.self::$PACKAGE_ID; // standard package
		$resp = SnapApi::send($verb, $path);
		$package = json_decode($resp['response']);

		// set price in cents
		$amount_in_cents = $package->amount;
		
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
			'url' => 'blank',
			'amount_in_cents' => $amount_in_cents,	
		);
		$this->load->view('common/html_header', $head);
		$this->load->view('signup/signup-jan2013', $head);
		$this->load->view('common/home_footer.php');
		$this->load->view('common/html_footer');
	}
	
	
	function setup()
	{
		// make sure form data is here
		if(!isset($_POST)) {
			$raven_client = new Raven_Client(SENTRY_DSN);
			$raven_client->captureMessage('Unable to create event. No form POST.');
			show_error('Unable to create the event.', 500);
		}

		// get package details
		$verb = 'GET';
		$path = 'package/'.self::$PACKAGE_ID; // standard package
		$resp = SnapApi::send($verb, $path);
		$package = json_decode($resp['response']);

		// set price in cents
		$amount_in_cents = $package->amount;
		$discount = 0;

		// if there is a promo code to process
		if (isset($_POST) && $_POST['promo-code-applied'] == 1 && isset($_POST['promo-code']))
		{
			// sanitize the data (ie. remove invalid characters and lowercase)
			$code = strtolower(preg_replace('/[^a-zA-Z0-9-_]/', '', $_POST['promo-code']));

			// only apply discount if coupon is valid
			if (array_key_exists($code, self::$COUPON_CODES)) {
				$discount = self::$COUPON_CODES[$code];
			}
		}

		// try and create the account/charge the user
		try {
			// create a Snapable order using the API
			$verb = 'POST';
			$path = '/order/account/';
			$params = array(
				'email' => $_POST['user']['email'],
				'password' => $_POST['user']['password'],
				'first_name' => $_POST['user']['first_name'],
				'last_name' => $_POST['user']['last_name'],
				'items' => array(
					'package' => self::$PACKAGE_ID, // the package id
					'account_addons' => array(), // required field, but empty
					'event_addons' => array(), // required field, but empty
				),
			);
			// add stripe token
			if (isset($_POST['stripeToken'])) {
				$params['stripeToken'] = $_POST['stripeToken'];
			}
			// add the coupon if there was one
			if (isset($coupon)) {
				$params['coupon'] = $coupon;
			}
			if ($discount > 0) {
				$params['discount'] = $discount;
			}
			$order_resp = SnapApi::send($verb, $path, $params);
			$order_response = json_decode($order_resp['response']);

			// get the orderID if it's successful
			if(isset($order_resp) && $order_resp['code'] == 201) {
				$idParts = explode('/', $order_response->resource_uri);
				$orderID = $idParts[3];
				$this->session->set_flashdata('orderID', $orderID);
			} 
			// can't create order
			else {
				$raven_client = new Raven_Client(SENTRY_DSN);
				$raven_client->captureMessage('Unable to process payment. There was a problem with the Credit Card.');
				throw new Exception('Unable to process payment.');
			}

			// we got this far, try and create the event
			//GET TIMEZONE
			$timezone_offset_seconds = $_POST['event']['tz_offset'] * 60;
			// SET TO UTC
			$start_timestamp = strtotime($_POST['event']['start_date'] . " " . $_POST['event']['start_time']) + ($timezone_offset_seconds);
			$start = gmdate( "c", $start_timestamp ); //date( "Y-m-d", $start_timestamp ) . "T" . date( "H:i:s", $start_timestamp ); // formatted: 2010-11-10T03:07:43 
			
			// CREATE END DATE
			if ( $_POST['event']['duration_type'] == "days" ) {
				$duration_in_seconds = $_POST['event']['duration_num'] * 86400;
			} else {
				$duration_in_seconds = $_POST['event']['duration_num'] * 3600;
			}
			$end_timestamp = $start_timestamp + $duration_in_seconds;
			$end = gmdate( "c", $end_timestamp );

			// create the actual event
			$verb = 'POST';
			$path = '/event/';
			$params = array(
				"account" => $order_response->account,
				"title" => $_POST['event']['title'],
			    "url" => $_POST['event']['url'],
			    "start" => $start,
			    "end" => $end,
			    "enabled" => true,
			    "tz_offset" => $_POST['event']['tz_offset'],
			);
			$event_resp = SnapApi::send($verb, $path, $params);
			$event_response = json_decode($event_resp['response']);

			// create the address
			if ( $event_resp['code'] != 201 ) {
				$raven_client = new Raven_Client(SENTRY_DSN);
				$raven_client->captureMessage('Unable to create event. There was no valid response after creating the event..');
				show_error('Unable to create the event.<br>We\'ve been notified and are looking into the problem.', 500);
			}
			
			// ADDRESS
			$verb = 'POST';
			$path = '/address/';
			$params = array(
				"event" => $event_response->resource_uri,
				"address" => $_POST['event']['location'],
				"lat" => $_POST['event']['lat'],
			    "lng" => $_POST['event']['lng'],
			);
			$resp = SnapApi::send($verb, $path, $params);

			// add the user as the first guest
			$verb = 'POST';
			$path = '/guest/';
			$params = array(
				'event' => $event_response->resource_uri,
				'email' => $_POST['user']['email'],
			    'name' => $_POST['user']['first_name'] . ' ' . $_POST['user']['last_name'],
			);
			$guest_resp = SnapApi::send($verb, $path, $params);

			// Snapable TEAM notification
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
			$this->email->from('robot@snapable.com', 'Snapable');
			$this->email->to('team@snapable.com');
			$this->email->subject($subject);
			$this->email->message($this->load->view('email/user_signup_html', $signup_details, true));
			$this->email->set_alt_message($this->load->view('email/user_signup_txt', $signup_details, true));		
			if (DEBUG == false) {
				$this->email->send();
			}

			// set sessions var to log user in
			//SnapAuth::signin_nohash($_POST['user']['email']);
			$hash = SnapAuth::snap_hash($_POST['user']['email'], $_POST['user']['password']);
			SnapAuth::signin($_POST['user']['email'], $hash);

			// redirect user
			$event_array = $this->account_model->eventDeets($order_response->account);
			$this->session->set_userdata('event_deets', $event_array);
			// redirect to the event
			//redirect('/event/'.$event_array['url']);
			// redirect to thank you page
			$this->session->set_flashdata('event', $event_array['url']);
			$this->session->set_flashdata('amount', $amount_in_cents);
			redirect('/signup/complete');

		} catch (Exception $e) {
			// keep the flash data if the user goes back
			//$this->session->keep_flashdata('package_id');
			//$this->session->keep_flashdata('package_price');
			// send the exception to sentry
			$raven_client = new Raven_Client(SENTRY_DSN);
			$raven_client->captureMessage('Unable to create event. There was no valid response after creating the event..');
			show_error('Unable to create the event.<br>We\'ve been notified and are looking into the problem.', 500);
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
			    echo json_encode(array(
			    	'status' => 200,
			    	'value' => (self::$COUPON_CODES[$code]/100),
			    ));
			} else {
			    echo json_encode(array('status' => 404));
			}
		} else {
			return 0;
		}		
	}
	
}