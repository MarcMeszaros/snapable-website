<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Internal extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        // load models
        $this->load->model('event_model','',TRUE);  

        // make sure 'super' users (ie. id < 1000 are logged in)
        $logged_in_user = $this->session->userdata('logged_in');
        $logged_in_parts = explode('/', $logged_in_user['resource_uri']);
        if (!isset($logged_in_parts[3]) || $logged_in_parts[3] >= 1000) {
            redirect('/account/signin');
        }
    }
    
    public function index()
    {
        redirect('/internal/dashboard');
    }

    public function dashboard()
    {
        $data = array(
            //'css' => base64_encode('assets/css/setup.css,assets/css/signin.css'),
            //'js' => base64_encode('assets/js/signin.js'),
            //'error' => $error,
            //'reset' => $reset
        );
        
        $params = array(
            'end__gte' => gmdate('c'),
            'order_by' => 'start',
        );
        $resp = $this->event_model->query($params);
        $data['events'] = $resp['response']['objects'];
        $this->load->view('internal/dashboard', $data);
    }
}
