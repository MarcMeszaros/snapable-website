<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Troubleshoot extends CI_Controller {

    public function index()
    {
        $head = array(
            'css' => array(
                'assets/css/header.css',
                'assets/css//troubleshoot/troubleshoot.css'
            ),
        );

        $this->load->view('common/html_header', $head);
        $this->load->view('common/header');
        echo '<div style="margin-top: 100px;">';
        $this->load->view('troubleshoot/index');
        echo '</div>';
        $this->load->view('common/html_footer');
    }
}
