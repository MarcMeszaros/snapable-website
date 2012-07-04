<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Min extends CI_Controller {

	function __construct()
	{
    	parent::__construct();
    	// MINIFY CSS
    	$this->load->driver('minify');
    	
    	//$this->load->model('photo_model','',TRUE);		    	
	}

	
	function _remap($method)
	{
		$param_offset = 2;
		
		// Default to index
		if ( ! method_exists($this, $method))
		{
		// We need one more param
		$param_offset = 1;
		$method = 'index';
		}
		
		// Since all we get is $method, load up everything else in the URI
		$params = array_slice($this->uri->rsegment_array(), $param_offset);
		
		// Call the determined method with all params
		call_user_func_array(array($this, $method), $params);
	} 
	 
	public function index()
	{
		$segments = $this->uri->total_segments();
  	
  		if ( $segments == 2 )
		{
			$files = base64_decode($this->uri->segment(2));
			$combine = explode(",",$files);
			echo $this->minify->combine_files($combine);
		} else {
			show_404();
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */