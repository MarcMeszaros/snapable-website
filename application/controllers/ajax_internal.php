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

        // alway check if it's an ajax call
        if (!IS_AJAX)
        {
            show_error('Not a proper AJAX call.', 403);
        }
    }

    public function total_signups($start=0, $end=null) {
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

    public function avg_event_photos($start=0, $end=null)
    {
        $end = (isset($end)) ? $end : time();

        // get events
        $verb = 'GET';
        $path = 'event';
        $params = array(
            'end__gte' => gmdate('c', $start),
            'end__lte' => gmdate('c', $end),
        );
        $resp = SnapApi::send($verb, $path, $params);
        $response = json_decode($resp['response'], true);
        $response_loop = json_decode($resp['response']);

        // get the inital count started
        $total_count = $response['meta']['total_count'];
        $avg = 0;
        foreach ($response['objects'] as $event) {
            $avg += $event['photo_count'];
        }

        // start looping through the pages of results
        while (isset($response_loop->meta->next)) {
            $resp_loop = SnapAPI::next($response_loop->meta->next);
            $response_loop = json_decode($resp_loop);

            // add to the average
            foreach ($response_loop->objects as $event) {
                $avg += $event->photo_count;
            }
        }

        // calculate the average
        $total_count = ($total_count > 0) ? $total_count : 1; // prevent division by 0 errors
        $avg = $avg/$total_count;

        // modify the response
        unset($response['objects']);
        $response['metrics'] = array(
            'avg' => $avg,
        );

        echo json_encode($response);
    }

    public function delete_event()
    {
        $event_id = $this->input->get_post('event_id');
        if($event_id) {
            // delete event
            $verb = 'DELETE';
            $path = 'event/'.$event_id;
            $resp = SnapApi::send($verb, $path);
            $this->output->set_status_header($resp['code']);

            echo $resp['response'];
        } else {
            $this->output->set_status_header('400');
        }
    }

    public function delete_user()
    {
        $user_id = $this->input->get_post('user_id');

        if ($user_id) {
            // delete user
            $verb = 'DELETE';
            $path = 'user/'.$user_id;
            $resp = SnapApi::send($verb, $path);
            $this->output->set_status_header($resp['code']);

            echo $resp['response'];
        } else {
            $this->output->set_status_header('400');
        }
    }

    public function delete_photo()
    {
        $photo_id = $this->input->get_post('photo_id');

        if ($photo_id) {
            // delete user
            $verb = 'DELETE';
            $path = 'photo/'.$photo_id;
            $resp = SnapApi::send($verb, $path);
            $this->output->set_status_header($resp['code']);

            echo $resp['response'];
        } else {
            $this->output->set_status_header('400');
        }
    }
}