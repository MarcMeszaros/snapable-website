<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checkout extends CI_Controller {

	function __construct()
	{
    	parent::__construct(); 
    	echo "&nbsp;";   
    	$this->data['css'] = base64_encode('assets/css/setup.css,assets/css/header.css,assets/css/site.css,assets/css/footer.css');			    	
	}
	
	public function index()
	{
		if ( isset($_COOKIE['phpCart']) )
		{
			echo $_COOKIE['phCart'];
		} else {
			echo "no cookies.";
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */