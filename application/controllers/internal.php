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
            'css' => array(
                'assets/css/internal/dashboard.css',
            ),
            'js' => array(
                'assets/js/internal/dashboard-metrics.js',
                'assets/js/internal/dashboard-delete.js',
            ),
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

    public function event()
    {
        require_https();
        $head = array(
            'ext_css' => array(
            ),
            'css' => array(
                'assets/css/internal/event.css',
            ),
            'ext_js' => array(
                '//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.20/jquery.form.js',
                '//maps.googleapis.com/maps/api/js?key=AIzaSyAofUaaxFh5DUuOdZHmoWETZNAzP1QEya0&sensor=false',
            ),
            'js' => array(
                'assets/js/internal/event.js',
            ),
            'title' => 'Internal Dashboard - Event',    
        );

        $this->load->view('common/html_header', $head);
        $this->load->view('internal/event');
        $this->load->view('common/html_footer');
    }

    public function ajax_create_event() {
        // we got this far, try and create the event
        //GET TIMEZONE
        $timezone_offset_seconds = $_POST['event']['tz_offset'] * 60;
        // SET TO UTC
        $start_timestamp = strtotime($_POST['event']['date'] . ' ' . $_POST['event']['time']) + ($timezone_offset_seconds);
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
            "account" => SnapApi::resource_uri('account', $_POST['event']['account_id']),
            "title" => $_POST['event']['title'],
            "url" => $_POST['event']['url'],
            "start" => $start,
            "end" => $end,
            "enabled" => true,
            "tz_offset" => $_POST['event']['tz_offset'],
        );
        $event_resp = SnapApi::send($verb, $path, $params);
        $event_response = json_decode($event_resp['response']);
        if ($event_resp['code'] == 201) {
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
        }
        $this->output->set_status_header($resp['code']);
    }

    public function ajax_check_url($url=null) {
        $url = (isset($url)) ? $url : $this->input->get_post('url', true);
        $verb = 'GET';
        $path = '/event/';
        $params = array(
            'url' => $url,
        );
        $resp = SnapApi::send($verb, $path, $params);
        $this->output->set_status_header($resp['code']);
        echo $resp['response'];
    }
}
