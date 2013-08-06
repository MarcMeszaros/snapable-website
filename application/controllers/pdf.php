<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pdf extends CI_Controller {

    public function index()
    {
        show_404();
    }

    public function demo()
    {
        $pdf = new SnapPdf();
        $pdf->title('Snapable Demo Event');
        $pdf->url('demo');
        $pdf->pin('1234');
        $pdf->Output('demo.pdf', 'D');
    }

    public function download($url=null) 
    {
        if (isset($url)) {
            // get event details
            $verb = 'GET';
            $path = '/event/';
            $params = array(
                'url' => $url,
            );
            $event_resp = SnapApi::send($verb, $path, $params);
            $event_result = json_decode($event_resp['response']);

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
    }

}