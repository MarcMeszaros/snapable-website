<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

    public function index()
    {
   
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
}