<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pdf extends CI_Controller {

    public function index()
    {
        show_404();
    }

    public function download($url=null) 
    {
        $session_owner = SnapAuth::is_logged_in();
        if (isset($url) && $session_owner) {
            // get event details
            $verb = 'GET';
            $path = '/event/';
            $params = array(
                'url' => $url,
            );
            $event_resp = SnapApi::send($verb, $path, $params);
            $event_result = json_decode($event_resp['response']);

            // get accounts the user belongs to
            $sessionIdParts = explode('/', $session_owner['user_uri']);
            $verb = 'GET';
            $path = 'user/'.$sessionIdParts[3];
            $user_resp = SnapApi::send($verb, $path);
            $user_result = json_decode($user_resp['response']);

            // make sure the the user belongs to the event account, and then delete
            if (in_array($event_result->objects[0]->account, $user_result->accounts) == true) {
                // Create new pdf
                $pdf = new SnapPdf();
                
                // fill in the data for the PDF
                $pdf->title($event_result->objects[0]->title);
                $pdf->url($event_result->objects[0]->url);
                $pdf->pin($event_result->objects[0]->pin);
                $pdf->Output($event_result->objects[0]->url . '.pdf', 'D');
            } else {
                $this->output->set_status_header('404');
            }

        } else {
            $this->output->set_status_header('404');
        }
    }

}