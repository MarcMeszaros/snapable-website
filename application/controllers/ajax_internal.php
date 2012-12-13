<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_internal extends CI_Controller {

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

    public function total_signups($start=0, $end=null) {
        if (!IS_AJAX)
        {
            show_error('Not an AJAX call.', 403);
        }
        $end = (isset($end)) ? $end : time();

        $verb = 'GET';
        $path = 'user';
        $params = array(
            'creation_date__gte' => gmdate('c', $start),
            'creation_date__lte' => gmdate('c', $end),
        );
        $resp = SnapApi::send($verb, $path, $params);

        echo $resp['response'];
    }

    public function past_events($start=0, $end=null) {
        if (!IS_AJAX)
        {
            show_error('Not an AJAX call.', 403);
        }
        $end = (isset($end)) ? $end : time();

        $verb = 'GET';
        $path = 'event';
        $params = array(
            'end__gte' => gmdate('c', $start),
            'end__lte' => gmdate('c', $end),
        );
        $resp = SnapApi::send($verb, $path, $params);

        echo $resp['response'];
    }

    public function photos_count($start=0, $end=null) {
        if (!IS_AJAX)
        {
            show_error('Not an AJAX call.', 403);
        }
        $end = (isset($end)) ? $end : time();

        $verb = 'GET';
        $path = 'photo';
        $params = array(
            'timestamp__gte' => gmdate('c', $start),
            'timestamp__lte' => gmdate('c', $end),
        );
        $resp = SnapApi::send($verb, $path, $params);

        echo $resp['response'];
    }

    public function upcoming_events($start=0, $end=null)
    {
        if (!IS_AJAX)
        {
            show_error('Not an AJAX call.', 403);
        }
        $end = (isset($end)) ? $end : time();

        // get upcoming events
        $verb = 'GET';
        $path = 'event';
        $params = array(
            'end__gte' => gmdate('c', $end),
            'order_by' => 'start',
        );
        $resp = SnapApi::send($verb, $path, $params);
        
        echo $resp['response'];
    }

    public function events_with_photo_count($photo_count, $start=0, $end=null)
    {
        if (!IS_AJAX)
        {
            show_error('Not an AJAX call.', 403);
        }
        $end = (isset($end)) ? $end : time();

        // get upcoming events
        $verb = 'GET';
        $path = 'event';
        $params = array(
            'photo_count__gte' => $photo_count,
            'end__gte' => gmdate('c', $start),
            'end__lte' => gmdate('c', $end),
        );
        $resp = SnapApi::send($verb, $path, $params);
        
        echo $resp['response'];
    }    
}