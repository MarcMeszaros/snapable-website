<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Download extends CI_Controller {
 
    public function index()
    {
        show_404();
    }

    public function photo($photo, $size=null) {
        $verb = 'GET';
        $path = '/photo/' . $photo . '/';
        $params = array();
        if (isset($size)) {
            $params['size'] = $size;
        }
        $headers = array(
            'Accept' => 'image/jpeg',
        );
        $resp = SnapApi::send($verb, $path, $params, $headers);
        $img = $resp['response'];

        // get photo data to create a unique filename
        $resp = SnapApi::send($verb, $path, $params);
        $result = json_decode($reps['response']);

        // send the output
        // TODO: make the unique name more user friendly, but unique, yet without including internal snapable info.
        $this->output->set_header('Content-Disposition: attachment; filename="snapable_'.md5($result->timestamp).'.jpg"');
        $this->output->set_content_type('jpeg'); // You could also use ".jpeg" which will have the full stop removed before looking in config/mimes.php
        $this->output->set_output($img);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */