<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('email');
    }

    public function index()
    {
        show_404();
    }

    /**
     * Handle event ajax calls.
     */
    public function put_event() {
        if (!IS_AJAX)
        {
            show_error('Not an AJAX call.', 403);
        }

        // get all the post variables
        $post = array();
        foreach (array_keys($_POST) as $key) 
        {
            $post[$key] = $this->input->post($key);
        }

        // update the address
        if (isset($post['address_id']) && (isset($post['address']) || isset($post['lat']) || isset($post['lng'])))
        {
            // update the address the event address
            $verb = 'PUT';
            $path = 'address/'.$post['address_id'];
            $params = array(
                'address' => $post['address'],
                'lat' => $post['lat'],
                'lng' => $post['lng'],
            );
            $resp = SnapApi::send($verb, $path, $params);

            // remove the values from $post
            unset($post['address_id']);
            unset($post['address']);
            unset($post['lat']);
            unset($post['lng']);
        }

        // process date/time stuff
        $duration = $post['duration_num'];
        if ($post['duration_type'] == 'hours') {
            $duration = $duration * 60 * 60;
        } else {
            $duration = $duration * 60 * 60 * 24;
        }

        // get the unix times
        // 'Jul 23, 2012 02:00 PM'
        $date = $post['start_date'].' '.strtolower($post['start_time']);
        $dateParts = explode(' ', $date);
        $start = 0;
        if ($dateParts[4] == 'am') {
            $dt = DateTime::createFromFormat('M d, Y h:i a', $date);
            $start = $dt->getTimestamp() + ($post['tz_offset'] * -60);
        } else {
            $dt = DateTime::createFromFormat('M d, Y h:i A', $date);
            $start = $dt->getTimestamp() + ($post['tz_offset'] * -60);
        }
        $end = $start + $duration;

        // format the values for the API call
        $post['start'] = date('Y-m-d H:i:s', $start);
        $post['end'] = date('Y-m-d H:i:s', $end);

        // unset variables
        unset($post['start_date']);
        unset($post['start_time']);
        unset($post['duration_num']);
        unset($post['duration_type']);

        // update the event
        $verb = 'PUT';
        $path = ($this->uri->segment(3) !== false) ? 'event/'.$this->uri->segment(3) : 'event';
        $resp = SnapApi::send($verb, $path, $post);

        echo $resp['response'];
    }

    public function timezone() {
        if (!IS_AJAX)
        {
            show_error('Not an AJAX call.', 403);
        }

        $lat = $this->input->get('lat');
        $lng = $this->input->get('lng');
        $timestamp = $this->input->get('timestamp');

        $url = 'https://maps.googleapis.com/maps/api/timezone/json?location='.$lat.','.$lng.'&timestamp='.$timestamp.'&sensor=false';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        // set various curl parameters
        curl_setopt($ch, CURLOPT_TIMEOUT, '5');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // execute the request and parse response
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        echo $response;
    }

    public function is_logged_in() {
        if (!IS_AJAX)
        {
            show_error('Not an AJAX call.', 403);
        }

        $logged_in = SnapAuth::is_logged_in();
        echo json_encode($logged_in);
    }

    public function is_guest_logged_in() {
        if (!IS_AJAX)
        {
            show_error('Not an AJAX call.', 403);
        }

        $logged_in = SnapAuth::is_guest_logged_in();
        echo json_encode($logged_in);
    }

    public function send_email() {
        if (!IS_AJAX)
        {
            show_error('Not an AJAX call.', 403);
        }

        // get the form values
        $to = $this->input->post('to', TRUE); //( isset($_POST['to']) ) ? $_POST['to']:"team@snapable.com";
        $from = $this->input->post('from', TRUE); //( isset($_POST['from']) ) ? $_POST['from']:"website@snapable.com";
        $subject = $this->input->post('subject', TRUE);
        $message = $this->input->post('message', TRUE);

        // set some defaults
        if (!$to) { $to = 'team@snapable.com'; }
        if (!$from) { $from = 'team@snapable.com'; }
        if (!$subject) { $subject = 'Snapable Automated Email'; }
        if (!$message) { $message = ''; }

        // send the email
        $this->email->from($from);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->set_alt_message($message);       
        if ($this->email->send()) 
        {
            echo "success";
        } else {
            echo "failed";
        }
    }
}