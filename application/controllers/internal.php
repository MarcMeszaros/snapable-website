<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Internal extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        // load models
        $this->load->model('event_model','',TRUE);
        $this->load->model('user_model','',TRUE);

        // make sure 'super' users (ie. id < 1000 are logged in)
        $logged_in_user = SnapAuth::is_logged_in();
        $logged_in_parts = explode('/', $logged_in_user['resource_uri']);
        if (!isset($logged_in_parts[3]) || $logged_in_parts[3] >= 1000) {
            show_404(); // returning 404 makes the internal dashboard more obscure
        }
    }
    
    public function index()
    {
        redirect('/internal/dashboard');
    }

    public function dashboard()
    {
        require_https();
        $data = array(
            'ext_css' => array(
                '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.2.1/css/bootstrap.min.css',
            ),
            'css' => array('assets/css/internal/dashboard.css'),
            'ext_js' => array(
                '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.2.1/bootstrap.min.js'
            ),
            'js' => array('assets/js/internal/dashboard-metrics.js'),
            'title' => 'Internal Dashboard',    
        );
        
        // get total signups
        $resp = $this->user_model->query();
        $data['total_signups'] = $resp['response']['meta']['total_count'];

        // events to date
        $params = array(
            'end__lte' => gmdate('c'),
        );
        $resp = $this->event_model->query($params);
        $data['total_events_to_date'] = $resp['response']['meta']['total_count'];

        // get upcoming events
        $params = array(
            'end__gte' => gmdate('c'),
            'order_by' => 'start',
        );
        $resp = $this->event_model->query($params);
        $data['events'] = $resp['response']['objects'];
        $data['total_upcoming_events'] = $resp['response']['meta']['total_count'];
        
        $this->load->view('common/html_header', $data);
        $this->load->view('internal/dashboard', $data);
        $this->load->view('common/html_footer', $data);
    }
}
