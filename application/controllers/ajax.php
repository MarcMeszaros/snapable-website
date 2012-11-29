<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

    public function index()
    {
   
    }

    /**
     * Handle event ajax calls.
     */
    public function event() {
        if (!IS_AJAX)
        {
            show_error('Not an AJAX call.', 403);
        }

        $post = array();
        foreach (array_keys($_POST) as $key) 
        {
            $post[$key] = $this->input->post($key);
        }

        if ($this->uri->segment(3) == 'update') {
            if (isset($post['id'])) {
                unset($post['id']);
            }

            $verb = 'PUT';
            $path = ($this->uri->segment(4) !== false) ? 'event/'.$this->uri->segment(4) : 'event';
            $resp = SnapApi::send($verb, $path, $post);

            echo $resp['response'];
        }
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
}