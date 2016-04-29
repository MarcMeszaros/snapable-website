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
		'nonprofitedu' => 4900, // added: 2014-02-20; valid_until: TBD
		'poptastic' => 1000, // added: 2013-01-31; valid_until: TBD
		'smartbride' => 1000, // added: 2013-01-31; valid_until: TBD
		'snaptrial2013' => 4900, // added: 2013-03-14; valid_until: TBD
		'snaptrial2014' => 4900, // added: 2014-02-20; valid_until: TBD
		'snaptrial2015' => 4900, // added: 2014-02-20; valid_until: TBD
		'snaptrial2016' => 4900, // added: 2014-02-20; valid_until: TBD
		'weddingful5986' => 4900, // added: 2013-02-06; valid_until: TBD
		'wr2013' => 1000, // added: 2013-01-17; valid_until: TBD
	);

	public static $PACKAGE_ID = 3;

	// countries
	public static $COUNTRIES = array(
	    'US' => 'United States', // added to the top
	    'AF' => 'Afghanistan',
	    'AX' => 'Åland Islands',
	    'AL' => 'Albania',
	    'DZ' => 'Algeria',
	    'AS' => 'American Samoa',
	    'AD' => 'Andorra',
	    'AO' => 'Angola',
	    'AI' => 'Anguilla',
	    'AQ' => 'Antarctica',
	    'AG' => 'Antigua and Barbuda',
	    'AR' => 'Argentina',
	    'AM' => 'Armenia',
	    'AW' => 'Aruba',
	    'AU' => 'Australia',
	    'AT' => 'Austria',
	    'AZ' => 'Azerbaijan',
	    'BS' => 'Bahamas',
	    'BH' => 'Bahrain',
	    'BD' => 'Bangladesh',
	    'BB' => 'Barbados',
	    'BY' => 'Belarus',
	    'BE' => 'Belgium',
	    'BZ' => 'Belize',
	    'BJ' => 'Benin',
	    'BM' => 'Bermuda',
	    'BT' => 'Bhutan',
	    'BO' => 'Bolivia, Plurinational State of',
	    'BQ' => 'Bonaire, Sint Eustatius and Saba',
	    'BA' => 'Bosnia and Herzegovina',
	    'BW' => 'Botswana',
	    'BV' => 'Bouvet Island',
	    'BR' => 'Brazil',
	    'IO' => 'British Indian Ocean Territory',
	    'BN' => 'Brunei Darussalam',
	    'BG' => 'Bulgaria',
	    'BF' => 'Burkina Faso',
	    'BI' => 'Burundi',
	    'KH' => 'Cambodia',
	    'CM' => 'Cameroon',
	    'CA' => 'Canada',
	    'CV' => 'Cape Verde',
	    'KY' => 'Cayman Islands',
	    'CF' => 'Central African Republic',
	    'TD' => 'Chad',
	    'CL' => 'Chile',
	    'CN' => 'China',
	    'CX' => 'Christmas Island',
	    'CC' => 'Cocos (Keeling) Islands',
	    'CO' => 'Colombia',
	    'KM' => 'Comoros',
	    'CG' => 'Congo',
	    'CD' => 'Congo, the Democratic Republic of the',
	    'CK' => 'Cook Islands',
	    'CR' => 'Costa Rica',
	    'CI' => 'Côte d\'Ivoire',
	    'HR' => 'Croatia',
	    'CU' => 'Cuba',
	    'CW' => 'Curaçao',
	    'CY' => 'Cyprus',
	    'CZ' => 'Czech Republic',
	    'DK' => 'Denmark',
	    'DJ' => 'Djibouti',
	    'DM' => 'Dominica',
	    'DO' => 'Dominican Republic',
	    'EC' => 'Ecuador',
	    'EG' => 'Egypt',
	    'SV' => 'El Salvador',
	    'GQ' => 'Equatorial Guinea',
	    'ER' => 'Eritrea',
	    'EE' => 'Estonia',
	    'ET' => 'Ethiopia',
	    'FK' => 'Falkland Islands (Malvinas)',
	    'FO' => 'Faroe Islands',
	    'FJ' => 'Fiji',
	    'FI' => 'Finland',
	    'FR' => 'France',
	    'GF' => 'French Guiana',
	    'PF' => 'French Polynesia',
	    'TF' => 'French Southern Territories',
	    'GA' => 'Gabon',
	    'GM' => 'Gambia',
	    'GE' => 'Georgia',
	    'DE' => 'Germany',
	    'GH' => 'Ghana',
	    'GI' => 'Gibraltar',
	    'GR' => 'Greece',
	    'GL' => 'Greenland',
	    'GD' => 'Grenada',
	    'GP' => 'Guadeloupe',
	    'GU' => 'Guam',
	    'GT' => 'Guatemala',
	    'GG' => 'Guernsey',
	    'GN' => 'Guinea',
	    'GW' => 'Guinea-Bissau',
	    'GY' => 'Guyana',
	    'HT' => 'Haiti',
	    'HM' => 'Heard Island and McDonald Islands',
	    'VA' => 'Holy See (Vatican City State)',
	    'HN' => 'Honduras',
	    'HK' => 'Hong Kong',
	    'HU' => 'Hungary',
	    'IS' => 'Iceland',
	    'IN' => 'India',
	    'ID' => 'Indonesia',
	    'IR' => 'Iran, Islamic Republic of',
	    'IQ' => 'Iraq',
	    'IE' => 'Ireland',
	    'IM' => 'Isle of Man',
	    'IL' => 'Israel',
	    'IT' => 'Italy',
	    'JM' => 'Jamaica',
	    'JP' => 'Japan',
	    'JE' => 'Jersey',
	    'JO' => 'Jordan',
	    'KZ' => 'Kazakhstan',
	    'KE' => 'Kenya',
	    'KI' => 'Kiribati',
	    'KP' => 'Korea, Democratic People\'s Republic of',
	    'KR' => 'Korea, Republic of',
	    'KW' => 'Kuwait',
	    'KG' => 'Kyrgyzstan',
	    'LA' => 'Lao People\'s Democratic Republic',
	    'LV' => 'Latvia',
	    'LB' => 'Lebanon',
	    'LS' => 'Lesotho',
	    'LR' => 'Liberia',
	    'LY' => 'Libya',
	    'LI' => 'Liechtenstein',
	    'LT' => 'Lithuania',
	    'LU' => 'Luxembourg',
	    'MO' => 'Macao',
	    'MK' => 'Macedonia, The Former Yugoslav Republic of',
	    'MG' => 'Madagascar',
	    'MW' => 'Malawi',
	    'MY' => 'Malaysia',
	    'MV' => 'Maldives',
	    'ML' => 'Mali',
	    'MT' => 'Malta',
	    'MH' => 'Marshall Islands',
	    'MQ' => 'Martinique',
	    'MR' => 'Mauritania',
	    'MU' => 'Mauritius',
	    'YT' => 'Mayotte',
	    'MX' => 'Mexico',
	    'FM' => 'Micronesia, Federated States of',
	    'MD' => 'Moldova, Republic of',
	    'MC' => 'Monaco',
	    'MN' => 'Mongolia',
	    'ME' => 'Montenegro',
	    'MS' => 'Montserrat',
	    'MA' => 'Morocco',
	    'MZ' => 'Mozambique',
	    'MM' => 'Myanmar',
	    'NA' => 'Namibia',
	    'NR' => 'Nauru',
	    'NP' => 'Nepal',
	    'NL' => 'Netherlands',
	    'NC' => 'New Caledonia',
	    'NZ' => 'New Zealand',
	    'NI' => 'Nicaragua',
	    'NE' => 'Niger',
	    'NG' => 'Nigeria',
	    'NU' => 'Niue',
	    'NF' => 'Norfolk Island',
	    'MP' => 'Northern Mariana Islands',
	    'NO' => 'Norway',
	    'OM' => 'Oman',
	    'PK' => 'Pakistan',
	    'PW' => 'Palau',
	    'PS' => 'Palestine, State of',
	    'PA' => 'Panama',
	    'PG' => 'Papua New Guinea',
	    'PY' => 'Paraguay',
	    'PE' => 'Peru',
	    'PH' => 'Philippines',
	    'PN' => 'Pitcairn',
	    'PL' => 'Poland',
	    'PT' => 'Portugal',
	    'PR' => 'Puerto Rico',
	    'QA' => 'Qatar',
	    'RE' => 'Réunion',
	    'RO' => 'Romania',
	    'RU' => 'Russian Federation',
	    'RW' => 'Rwanda',
	    'BL' => 'Saint Barthélemy',
	    'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
	    'KN' => 'Saint Kitts and Nevis',
	    'LC' => 'Saint Lucia',
	    'MF' => 'Saint Martin (French part)',
	    'PM' => 'Saint Pierre and Miquelon',
	    'VC' => 'Saint Vincent and the Grenadines',
	    'WS' => 'Samoa',
	    'SM' => 'San Marino',
	    'ST' => 'Sao Tome and Principe',
	    'SA' => 'Saudi Arabia',
	    'SN' => 'Senegal',
	    'RS' => 'Serbia',
	    'SC' => 'Seychelles',
	    'SL' => 'Sierra Leone',
	    'SG' => 'Singapore',
	    'SX' => 'Sint Maarten (Dutch part)',
	    'SK' => 'Slovakia',
	    'SI' => 'Slovenia',
	    'SB' => 'Solomon Islands',
	    'SO' => 'Somalia',
	    'ZA' => 'South Africa',
	    'GS' => 'South Georgia and the South Sandwich Islands',
	    'SS' => 'South Sudan',
	    'ES' => 'Spain',
	    'LK' => 'Sri Lanka',
	    'SD' => 'Sudan',
	    'SR' => 'Suriname',
	    'SJ' => 'Svalbard and Jan Mayen',
	    'SZ' => 'Swaziland',
	    'SE' => 'Sweden',
	    'CH' => 'Switzerland',
	    'SY' => 'Syrian Arab Republic',
	    'TW' => 'Taiwan, Province of China',
	    'TJ' => 'Tajikistan',
	    'TZ' => 'Tanzania, United Republic of',
	    'TH' => 'Thailand',
	    'TL' => 'Timor-Leste',
	    'TG' => 'Togo',
	    'TK' => 'Tokelau',
	    'TO' => 'Tonga',
	    'TT' => 'Trinidad and Tobago',
	    'TN' => 'Tunisia',
	    'TR' => 'Turkey',
	    'TM' => 'Turkmenistan',
	    'TC' => 'Turks and Caicos Islands',
	    'TV' => 'Tuvalu',
	    'UG' => 'Uganda',
	    'UA' => 'Ukraine',
	    'AE' => 'United Arab Emirates',
	    'GB' => 'United Kingdom',
	    //'US' => 'United States',
	    'UM' => 'United States Minor Outlying Islands',
	    'UY' => 'Uruguay',
	    'UZ' => 'Uzbekistan',
	    'VU' => 'Vanuatu',
	    'VE' => 'Venezuela, Bolivarian Republic of',
	    'VN' => 'Viet Nam',
	    'VG' => 'Virgin Islands, British',
	    'VI' => 'Virgin Islands, U.S.',
	    'WF' => 'Wallis and Futuna',
	    'EH' => 'Western Sahara',
	    'YE' => 'Yemen',
	    'ZM' => 'Zambia',
	    'ZW' => 'Zimbabwe'
	);

	public static $US_STATES = array(
	    'AL' => 'Alabama',
	    'AK' => 'Alaska',
	    'AZ' => 'Arizona',
	    'AR' => 'Arkansas',
	    'CA' => 'California',
	    'CO' => 'Colorado',
	    'CT' => 'Connecticut',
	    'DE' => 'Delaware',
	    'DC' => 'District of Columbia',
	    'FL' => 'Florida',
	    'GA' => 'Georgia',
	    'HI' => 'Hawaii',
	    'ID' => 'Idaho',
	    'IL' => 'Illinois',
	    'IN' => 'Indiana',
	    'IA' => 'Iowa',
	    'KS' => 'Kansas',
	    'KY' => 'Kentucky',
	    'LA' => 'Louisiana',
	    'ME' => 'Maine',
	    'MD' => 'Maryland',
	    'MA' => 'Massachusetts',
	    'MI' => 'Michigan',
	    'MN' => 'Minnesota',
	    'MS' => 'Mississippi',
	    'MO' => 'Missouri',
	    'MT' => 'Montana',
	    'NE' => 'Nebraska',
	    'NV' => 'Nevada',
	    'NH' => 'New Hampshire',
	    'NJ' => 'New Jersey',
	    'NM' => 'New Mexico',
	    'NY' => 'New York',
	    'NC' => 'North Carolina',
	    'ND' => 'North Dakota',
	    'OH' => 'Ohio',
	    'OK' => 'Oklahoma',
	    'OR' => 'Oregon',
	    'PA' => 'Pennsylvania',
	    'RI' => 'Rhode Island',
	    'SC' => 'South Carolina',
	    'SD' => 'South Dakota',
	    'TN' => 'Tennessee',
	    'TX' => 'Texas',
	    'UT' => 'Utah',
	    'VT' => 'Vermont',
	    'VA' => 'Virginia',
	    'WA' => 'Washington',
	    'WV' => 'West Virginia',
	    'WI' => 'Wisconsin',
	    'WY' => 'Wyoming',
	);

	public static $CA_STATES = array(
	    'AB' => 'Alberta',
	    'BC' => 'British Columbia',
	    'MB' => 'Manitoba',
	    'NB' => 'New Brunswick',
	    'NL' => 'Newfoundland and Labrador',
	    'NT' => 'Northwest Territories',
	    'NS' => 'Nova Scotia',
	    'NU' => 'Nunavut',
	    'ON' => 'Ontario',
	    'PE' => 'Prince Edward Island',
	    'QC' => 'Quebec',
	    'SK' => 'Saskatchewan',
	    'YT' => 'Yukon Territory',
	);

	function __construct()
	{
    	parent::__construct();
    	$this->load->library('email');
    	$this->load->model('account_model','',TRUE);

    	$this->load->library('email');
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

		// get package details
		$verb = 'GET';
		$path = 'package/'.self::$PACKAGE_ID; // standard package
		$resp = SnapApi::send($verb, $path);
		$package = json_decode($resp['response']);

		// failed to get package
		if ($resp['code'] != 200) {
			Log::e('Unable to initialize signup process.');
			show_error('Unable to initialize signup process.<br>We\'ve been automatically notified and are looking into the problem.', 500);
		}

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
			'countries' => self::$COUNTRIES,
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
			Log::e('Unable to create event. No form POST.');
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
		$coupon = '';

		// if there is a promo code to process
		if (isset($_POST['promo-code-applied']))
		{
			// sanitize the data (ie. remove invalid characters and lowercase)
			$code = strtolower(preg_replace('/[^a-zA-Z0-9-_]/', '', $_POST['promo-code-applied']));

			// only apply discount if coupon is valid
			if (array_key_exists($code, self::$COUPON_CODES)) {
				$discount = self::$COUPON_CODES[$code];
				$coupon = $code;
			}
		}

		// try and create the account/charge the user
		try {
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
				// event
				'title' => $_POST['event']['title'],
				'url' => $_POST['event']['url'],
				'start' => $start,
				'end' => $end,
				'tz_offset' => $_POST['event']['tz_offset'],
				'address' => $_POST['event']['location'],
				'lat' => $_POST['event']['lat'],
				'lng' => $_POST['event']['lng'],
			);
			// add stripe token
			if (isset($_POST['stripeToken'])) {
				$params['stripeToken'] = $_POST['stripeToken'];
			}
			// add the coupon if there was one
			if (strlen($coupon) > 0) {
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
				Log::e('Unable to process payment. There was a problem with the Credit Card.');
				throw new Exception('Unable to process payment.');
			}

			// Snapable TEAM notification
			$signup_details = array(
				'start_timestamp' => $start_timestamp,
				'end_timestamp' => $end_timestamp,
				'email_address' => $_POST['user']['email'],
				'affiliate' => '',
				'total' => $order_response->amount,
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
			Log::e('Unable to create event. There was no valid response after creating the event.');
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
			$data['amount_total'] = $amount_sale;
			$data['order_id'] = $orderID;
		}

		$head = array(
            'css' => array('assets/css/loader.css'),
            'facebook_pixel' => true,
        );

		// load up the view
		$this->load->view('common/html_header', $head);
		$this->load->view('signup/complete', $data);
		$this->load->view('common/html_footer');
	}


	function check() {
		if ( isset($_GET['email']) ) {
			$verb = 'GET';
			$path = '/user/';
			$params = array(
				'email' => $this->input->get('email', true),
			);
			$resp = SnapApi::send($verb, $path, $params);
			$this->output->set_status_header($resp['code']);
			echo $resp['response'];
		}
		else if ( isset($_GET['url']) ) {
			$verb = 'GET';
			$path = '/event/';
			$params = array(
				'url' => $this->input->get('url', true),
			);
			$resp = SnapApi::send($verb, $path, $params);
			$this->output->set_status_header($resp['code']);
			echo $resp['response'];
		} else {
			echo '{ "status": 404 }';
		}
	}

	function promo() {
		$numargs = func_num_args();

		if ( IS_AJAX && isset($_GET['code']) && ($numargs == 0 || $numargs == 1) ) {
			// sanitize the data (ie. remove invalid characters and lowercase)
			$code = strtolower(preg_replace('/[^a-zA-Z0-9-_]/', '', $_GET['code']));

			if ( array_key_exists($code, self::$COUPON_CODES) ) {
			    // success
			    echo json_encode(array(
			    	'status' => 200,
			    	'value' => (self::$COUPON_CODES[$code]),
			    ));
			} else {
			    echo json_encode(array('status' => 404));
			}
		} else {
			return 0;
		}
	}

	function states($country) {
		// if us
		if ($country == 'US') {
			echo json_encode(self::$US_STATES);
		} else if ($country == 'CA') {
			echo json_encode(self::$CA_STATES);
		}
	}

}
