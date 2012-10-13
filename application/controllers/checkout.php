<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checkout extends CI_Controller {

	function __construct()
	{
    	parent::__construct(); 
    	echo "&nbsp;";   
    	
    	if($this->session->userdata('logged_in'))
		{
	    	$event_data = $this->session->userdata('event_deets');
	    	$event_url = "/event/" . $event_data['url'];
	    } else {
		    $event_url = "/account";
	    }
	    
    	$this->head = array(
			'noTagline' => true,
			'css' => base64_encode('assets/css/signin.css,assets/css/setup.css,assets/css/checkout.css,assets/css/header.css,assets/css/footer.css'),
			'js' => base64_encode('assets/js/mustache.js,assets/js/jquery-Mustache.js,assets/js/checkout.js'),
			'title' => "Get Prints from Snapable",
			'type' => "checkout",
			'url' => $event_url
		);
		
		if ($this->session->userdata('logged_in'))
		{
			$session_owner = $this->session->userdata('logged_in');
			
			if ( $session_owner['loggedin'] == true )
			{
				$ownerLoggedin = true;
				$this->head["loggedInBar"] = "owner"; 
			} else {
				$ownerLoggedin = false;
			}
		} 
		else if($this->session->userdata('guest_login'))
		{
			$session_guest = $this->session->userdata('guest_login');
			
			if ( $session_guest['loggedin'] == true )
			{
				$guestLoggedin = true;
				$this->head["loggedInBar"] = "guest"; 
			} else {
				$guestLoggedin = false;
			}
		} else {
			
		}
		
		if ( substr($_COOKIE['phCart'], 0, 1) == "," )
		{
			$cartContents = substr($_COOKIE['phCart'], 1);
		} else {
			$cartContents = $_COOKIE['phCart'];
		}
		
		$this->data = array(
			'photos' => $cartContents
		);		    	
	}

	public function index()
	{
		if ( isset($_COOKIE['phCart']) )
		{
			$step = array(
				'step' => 'review'
			);
			
			$this->load->view('common/header2', $this->head);
			$this->load->view('checkout/steps', $step);
			$this->load->view('checkout/index', $this->data);
			$this->load->view('common/footer');
		} else {
			echo "no cookies.";
		}
	}
	
	function shipping()
	{
		if ( isset($_COOKIE['phCart']) )
		{
			$step = array(
				'step' => 'shipping'
			);
			$this->head['js'] = base64_encode('assets/js/checkout-shipping.js'); 
			
			$this->load->view('common/header2', $this->head);
			$this->load->view('checkout/steps', $step);
			$this->load->view('checkout/shipping', $this->data);
			$this->load->view('common/footer');
		} else {
			echo "no cookies.";
		}
	}
	
	function billing()
	{
		if ( isset($_COOKIE['phCart']) )
		{
			$step = array(
				'step' => 'billing'
			);
			$this->head['js'] = base64_encode('assets/js/checkout-billing.js'); 
			
			$this->load->view('common/header2', $this->head);
			$this->load->view('checkout/steps', $step);
			$this->load->view('checkout/billing', $this->data);
			$this->load->view('common/footer');
		} else {
			echo "no cookies.";
		}
	}
	
	function complete()
	{
		if ( isset($_COOKIE['phCart']) )
		{
			$step = array(
				'step' => 'complete'
			);
			$this->head['js'] = base64_encode('assets/js/checkout-complete.js'); 
			
			$this->load->view('common/header2', $this->head);
			$this->load->view('checkout/steps', $step);
			$this->load->view('checkout/complete', $this->data);
			$this->load->view('common/footer');
		} else {
			echo "no cookies.";
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */