<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('email');

        // always check if it's an AJAX call
        if (!IS_AJAX)
        {
            show_error('Not a proper AJAX call.', 403);
        }
    }

    public function index()
    {
        show_404();
    }

    /**
     * Handle event ajax calls.
     */
    public function put_event() {
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
        if (isset($post['start_date']) && isset($post['start_time']) && isset($post['duration_num']) && isset($post['duration_type'])) {
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
        }

        // update the event
        if ($this->uri->segment(3) !== false) {
            $session_owner = SnapAuth::is_logged_in();
            if ($session_owner) {
                // get event session details
                $verb = 'GET';
                $path = 'event/'.$this->uri->segment(3);
                $event_resp = SnapApi::send($verb, $path);
                $event_result = json_decode($event_resp['response']);

                // get accounts the user belongs to
                $sessionIdParts = explode('/', $session_owner['user_uri']);
                $verb = 'GET';
                $path = 'user/'.$sessionIdParts[3];
                $user_resp = SnapApi::send($verb, $path);
                $user_result = json_decode($user_resp['response']);

                // make sure the the user belongs to the event account, and then delete
                if (in_array($event_result->account, $user_result->accounts) == true) {
                    // tweak the data
                    if (isset($post['cover'])) {
                        $post['cover'] = '/'.API_VERSION.'/photo/'.$post['cover'].'/';
                    }

                    $verb = 'PUT';
                    $path = 'event/'.$this->uri->segment(3);
                    $resp = SnapApi::send($verb, $path, $post);

                    $this->output->set_status_header($resp['code']);
                    echo $resp['response'];
                } else {
                    $this->output->set_status_header('404');
                }
            } else {
                $this->output->set_status_header('404');
            }
        } else {
            $verb = 'PUT';
            $path = ($this->uri->segment(3) !== false) ? 'event/'.$this->uri->segment(3) : 'event';
            $resp = SnapApi::send($verb, $path, $post);

            $this->output->set_status_header($resp['code']);
            echo $resp['response'];
        }
    }

    public function timezone() {
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
        $logged_in = SnapAuth::is_logged_in();
        echo json_encode($logged_in);
    }

    public function is_guest_logged_in() {
        $logged_in = SnapAuth::is_guest_logged_in();
        echo json_encode($logged_in);
    }

    public function send_email() {
        // check for bots
        foreach ($_POST['re-cap'] as $key => $value) {
            if ($value != '') {
                $this->output->set_status_header(403);
                Log::i('SPAM BOT', $_POST);
                return;
            }
        }

        // get the form values
        $to = $this->input->post('to', TRUE); //( isset($_POST['to']) ) ? $_POST['to']:"team@snapable.com";
        $from = $this->input->post('from', TRUE); //( isset($_POST['from']) ) ? $_POST['from']:"website@snapable.com";
        $subject = $this->input->post('subject', TRUE);
        $message = $this->input->post('message', TRUE);

        // set some defaults
        if (!$to) { $to = 'support@snapable.com'; }
        if (!$from) { $from = 'robot@snapable.com'; }
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
            $this->output->set_status_header(200);
            echo "success";
        } else {
            $this->output->set_status_header(500);
            echo "failed";
        }
    }

    /*  */
    public function delete_photo($photo) {
        $session_owner = SnapAuth::is_logged_in();
        if ($session_owner) {
            // get photo details
            $verb = 'GET';
            $path = 'photo/'.$photo;
            $photo_resp = SnapApi::send($verb, $path);
            $photo_result = json_decode($photo_resp['response']);
            $photoEventParts = explode('/', $photo_result->event);

            // get event session details
            $verb = 'GET';
            $path = 'event/'.$photoEventParts[3];
            $event_resp = SnapApi::send($verb, $path);
            $event_result = json_decode($event_resp['response']);

            // get accounts the user belongs to
            $sessionIdParts = explode('/', $session_owner['user_uri']);
            $verb = 'GET';
            $path = 'user/'.$sessionIdParts[3];
            $user_resp = SnapApi::send($verb, $path);
            $user_result = json_decode($user_resp['response']);

            // make sure the the user belongs to the event account, and then delete
            if (in_array($event_result->account, $user_result->accounts) == true) {
                $verb = 'DELETE';
                $path = 'photo/'.$photo;
                $resp = SnapApi::send($verb, $path);
                $this->output->set_status_header($resp['code']);
            } else {
                $this->output->set_status_header('404');
            }
        } else {
            $this->output->set_status_header('404');
        }
    }

    public function delete_guest($guest_id)
    {
        $session_owner = SnapAuth::is_logged_in();
        if ($session_owner) {
            // get photo details
            $verb = 'GET';
            $path = 'guest/'.$guest_id;
            $guest_resp = SnapApi::send($verb, $path);
            $guest_result = json_decode($guest_resp['response']);
            $guestEventParts = explode('/', $guest_result->event);

            // get event session details
            $verb = 'GET';
            $path = 'event/'.$guestEventParts[3];
            $event_resp = SnapApi::send($verb, $path);
            $event_result = json_decode($event_resp['response']);

            // get accounts the user belongs to
            $sessionIdParts = explode('/', $session_owner['user_uri']);
            $verb = 'GET';
            $path = 'user/'.$sessionIdParts[3];
            $user_resp = SnapApi::send($verb, $path);
            $user_result = json_decode($user_resp['response']);

            // make sure the the user belongs to the event account, and then delete
            if (in_array($event_result->account, $user_result->accounts) == true) {
                // delete user
                $verb = 'DELETE';
                $path = 'guest/'.$guest_id;
                $resp = SnapApi::send($verb, $path);
                $this->output->set_status_header($resp['code']);
                
                echo $resp['response'];
            } else {
                $this->output->set_status_header('404');
            }
        } else {
            $this->output->set_status_header('404');
        }
    }
}