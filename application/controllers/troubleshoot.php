<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Troubleshoot extends CI_Controller {
     
    public function index()
    {
        $head = array(
            'css' => array(
                'assets/css/header.css',
                'assets/css/footer-short.css',
                'assets/css/troubleshoot.css'
            ),
        );

        $this->load->view('common/html_header', $head);
        $this->load->view('common/header', array('linkHome' => true));
        $this->load->view('troubleshoot/index');
        $this->load->view('common/html_footer');
    }
}