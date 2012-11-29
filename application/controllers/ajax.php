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
}