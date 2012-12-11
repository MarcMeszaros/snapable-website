<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checkout extends CI_Controller {

	function __construct()
	{
    	parent::__construct(); 

    	if($this->session->userdata('logged_in'))
		{
	    	$event_data = $this->session->userdata('event_deets');
	    	$event_url = "/event/" . $event_data['url'];
	    } else {
		    $event_url = "/account";
	    }
	    
    	$this->head = array(
			'noTagline' => true,
			'css' => array(
				'assets/css/signin.css',
				'assets/css/setup.css',
				'assets/css/checkout.css',
				'assets/css/header.css',
				'assets/css/footer.css'
			),
			'js' => array(
				'assets/js/mustache.js',
				'assets/js/jquery-Mustache.js',
				'assets/js/checkout.js'
			),
			'title' => "Get Prints from Snapable",
			'type' => "checkout",
			'url' => $event_url
		);
		
		if ($this->session->userdata('logged_in'))
		{
			$session_owner = $this->session->userdata('logged_in');
			
			if ( $session_owner['loggedin'] == true )
			{
				$email = $session_owner['email'];
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
				$email = "unknown@snapable.com";
				$guestLoggedin = true;
				$this->head["loggedInBar"] = "guest"; 
			} else {
				$guestLoggedin = false;
			}
		} else {
			redirect("/");
		}
		
		if ( substr($_COOKIE['phCart'], 0, 1) == "," )
		{
			$cartContents = substr($_COOKIE['phCart'], 1);
		} else {
			$cartContents = $_COOKIE['phCart'];
		}
		
		$this->data = array(
			'photos' => $cartContents,
			'email' => $email
		);		    	
	}

	public function index()
	{
		if ( isset($_COOKIE['phCart']) )
		{
			$step = array(
				'step' => 'review'
			);
			
			$this->load->view('common/html_header', $this->head);
			$this->load->view('common/header2', $this->head);
			$this->load->view('checkout/steps', $step);
			$this->load->view('checkout/index', $this->data);
			$this->load->view('common/footer');
			$this->load->view('common/html_footer');
		} else {
			echo "no cookies.";
		}
	}
	
	function shipping()
	{
		if ( isset($_COOKIE['phCart']) || isset($_COOKIE['upgrades']) )
		{
			$step = array(
				'step' => 'shipping'
			);
			$this->head['js'] = array('assets/js/checkout-shipping.js'); 
			
			// CHECK ORDER CONTENTS
			if ( isset($_COOKIE['phCart']) )
			{
				$contents = "<ul>";
				$subtotal = 0;
				
				if ( isset($_COOKIE['upgrades']) && $_COOKIE['upgrades'] != "" )
				{
					$upgrades = explode(",", $_COOKIE['upgrades']);
					
					foreach ( $upgrades as $u )
					{
						if ( $u == 2 )
						{
							$upgrade_name = 12;
							$upgrade_amount = 11;
						}
						else if ( $u == 3 )
						{
							$upgrade_name = 24;
							$upgrade_amount = 19;
						}
						else if ( $u == 4 )
						{
							$upgrade_name = 36;
							$upgrade_amount = 27;
						}
					
						$contents .= "<li>
							<div class='orderContentsLeft'>
								<div class='orderContentsItem'><span>1 x </span>" . $upgrade_name . " Print Upgrade </div>
							</div>
							<div class='orderContentsRight'>$" . $upgrade_amount . "</div>
						</li>";
						
						$subtotal = $subtotal + $upgrade_amount;
					}
					
					$contents .= "</ul>";
					$contents .= "<div class='orderContentsLeft'>
						<div class='orderContentsItem'>Subtotal:</div>
					</div>
					<div class='orderContentsRight'>$" . $subtotal . "</div>
					<div class='clearit'>&nbsp;</div>
					
					<div class='orderContentsLeft'>
						<div class='orderContentsItem'>Shipping:</div>
					</div>
					<div class='orderContentsRight freeshipping'>FREE</div>
					<div class='clearit'>&nbsp;</div>
					
					<div class='orderContentsLeft'>
						<div class='orderContentsItem'>Total:</div>
					</div>
					<div class='orderContentsRight'>$" . $subtotal . "</div>
					<div class='clearit'>&nbsp;</div>";
				}
				$this->data['order_contents'] = $contents;
			} else {
				redirect($this->head['url']);
			}

			$this->load->view('common/html_header', $this->head);
			$this->load->view('common/header2', $this->head);
			$this->load->view('checkout/steps', $step);
			$this->load->view('checkout/shipping', $this->data);
			$this->load->view('common/footer');
			$this->load->view('common/html_footer');
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
			$this->head['js'] = array('assets/js/checkout-billing.js');
			$this->head['stripe'] = true;
			
			$allowed_hosts = array('snapable.com', 'www.snapable.com', 'internal.snapable.com');
			if (!isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $allowed_hosts))
			{
				$this->head['stripe_key'] = "***REMOVED***"; 
			} else {
				$this->head['stripe_key'] = "pk_Jfq6cp5WGO4JHBQgAtz4zJOeTftLf"; 	
			}
			
			// CHECK ORDER CONTENTS
			if ( isset($_COOKIE['phCart']) )
			{
				$contents = "<ul>";
				$subtotal = 0;
				
				if ( isset($_COOKIE['upgrades']) && $_COOKIE['upgrades'] != "" )
				{
					$upgrades = explode(",", $_COOKIE['upgrades']);
					
					foreach ( $upgrades as $u )
					{
						if ( $u == 2 )
						{
							$upgrade_name = 12;
							$upgrade_amount = 11;
						}
						else if ( $u == 3 )
						{
							$upgrade_name = 24;
							$upgrade_amount = 19;
						}
						else if ( $u == 4 )
						{
							$upgrade_name = 36;
							$upgrade_amount = 27;
						}
					
						$contents .= "<li>
							<div class='orderContentsLeft'>
								<div class='orderContentsItem'><span>1 x </span>" . $upgrade_name . " Print Upgrade </div>
							</div>
							<div class='orderContentsRight'>$" . $upgrade_amount . "</div>
						</li>";
						
						$subtotal = $subtotal + $upgrade_amount;
					}
					
					$contents .= "</ul>";
					$contents .= "<div class='orderContentsLeft'>
						<div class='orderContentsItem'>Subtotal:</div>
					</div>
					<div class='orderContentsRight'>$" . $subtotal . "</div>
					<div class='clearit'>&nbsp;</div>
					
					<div class='orderContentsLeft'>
						<div class='orderContentsItem'>Shipping:</div>
					</div>
					<div class='orderContentsRight freeshipping'>FREE</div>
					<div class='clearit'>&nbsp;</div>
					
					<div class='orderContentsLeft'>
						<div class='orderContentsItem'>Total:</div>
					</div>
					<div class='orderContentsRight'>$" . $subtotal . "</div>
					<div class='clearit'>&nbsp;</div>";
				}
				$this->data['order_contents'] = $contents;
			} else {
				redirect($this->head['url']);
			}
			
			$this->load->view('common/html_header', $this->head);
			$this->load->view('common/header2', $this->head);
			$this->load->view('checkout/steps', $step);
			$this->load->view('checkout/billing', $this->data);
			$this->load->view('common/footer');
			$this->load->view('common/html_footer');
		} else {
			echo "no cookies.";
		}
	}
	
	
	function pay()
	{

		$this->load->library( 'stripe' );
		
		if ($_POST) {
			if ( !isset($_POST['stripeToken']) )
			{
				echo 'FAIL';
			} else {
				$amount_in_cents = 10 * 100;
				$card = $_POST['stripeToken'];
				$desc = "1 month of Scratchpad";
				$email = $_POST['email'];
				
				$cust = json_decode($this->stripe->customer_create( $card, $email ));
				
				if ( isset($cust->error) )
				{
					//echo $cust->error->message;
					redirect("/checkout/error");
				} else {
					
					$charge = json_decode($this->stripe->charge_customer( $amount_in_cents, $cust->id, $desc ));
					
					if ( isset($charge->error) )
					{
						//echo $charge->error->message;
						redirect("/checkout/error");
					} else {
						redirect("/checkout/success");
					}
					
				}
			}
		}
	}
	
	
	function success()
	{
		if ( isset($_COOKIE['phCart']) )
		{
			// remove phCart and upgrades cookies
			
			$step = array(
				'step' => 'complete'
			);
			$this->head['js'] = array('assets/js/checkout-complete.js'); 
			
			$this->load->view('common/html_header', $this->head);
			$this->load->view('common/header2', $this->head);
			$this->load->view('checkout/steps', $step);
			$this->load->view('checkout/complete', $this->data);
			$this->load->view('common/footer');
			$this->load->view('common/html_footer');
		} else {
			echo "no cookies.";
		}
	}	
	
	function address()
	{
		if ( IS_AJAX && isset($_POST) )
		{
			$this->load->model('checkout_model','',TRUE);
			echo $this->checkout_model->addAddress($_POST);
		} else {
			show_404();
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */