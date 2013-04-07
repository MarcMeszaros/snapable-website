<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . '/../libs/tcpdf/tcpdf.php');
require_once(dirname(__FILE__) . '/../libs/fpdi/fpdi.php');

class SnapPdf extends FPDI {

    function __construct() {
        parent::__construct('L', 'mm', 'USLETTER');

        // add a page and set defaults 
        $this->AddPage(); 
        $this->SetAutoPageBreak(false);
        //set some defaults
        $this->SetAuthor('Snapable');
        $this->SetFont('vegur');
    }

    /**
    * "Remembers" the template id of the imported page
    */ 
    var $_tplIdx;

    function Header() {
        if (is_null($this->_tplIdx)) { 
            $this->setSourceFile(dirname(__FILE__) . '/../classes/card_template.pdf'); 
            $this->_tplIdx = $this->importPage(1); 
        } 
        $this->useTemplate($this->_tplIdx);
    }

    function Footer() {

    }

    public function title($title='') {
        $this->SetTitle($title);
    }

    /**
     * Setup the URL
     */
    public function url($url='') {
        // === add the "no phone, no problem" text === \\
        $this->SetFont('vegurb');
        $this->SetFontSize(10.0);
        $this->SetTextColor(0,0,0);
        $noProblemText = 'No iPhone or Android phone? No problem!';
        
        // top left quarter
        $this->SetXY(4.5, 87);
        $this->Write(0, $noProblemText);

        // top right quarter
        $this->SetXY(143.75, 87);
        $this->Write(0, $noProblemText);

        // bottom left quarter
        $this->SetXY(4.5, 195);
        $this->Write(0, $noProblemText);

        // bottom right quarter
        $this->SetXY(143.75, 195);
        $this->Write(0, $noProblemText);

        // === add the event url === \\
        $this->SetFont('vegur');
        $this->SetFontSize(10.0);
        $this->SetTextColor(0,0,0);
        $baseEventUrl = 'https://snapable.com/event/';

        // top left quarter
        $this->SetXY(4.5, 97);
        $this->Write(0, $baseEventUrl . $url);

        // top right quarter
        $this->SetXY(143.75, 97);
        $this->Write(0, $baseEventUrl . $url);

        // bottom left quarter
        $this->SetXY(4.5, 205);
        $this->Write(0, $baseEventUrl . $url);

        // bottom right quarter
        $this->SetXY(143.75, 205);
        $this->Write(0, $baseEventUrl . $url);
    }    

    /**
     * Setup the PIN
     */
    public function pin($pin='') {
        // set the font size and color
        $this->SetFont('vegurb');
        $this->SetFontSize(20.0);
        $this->SetTextColor(255,255,255); 
        
        // top left quarter
        $this->SetXY(102, 93); 
        $this->Write(0, $pin);
        
        // top right quarter
        $this->SetXY(241.5, 93); 
        $this->Write(0, $pin);

        // bottom left quarter
        $this->SetXY(102, 200.5); 
        $this->Write(0, $pin);

        // bottom right quarter
        $this->SetXY(241.5, 200.5); 
        $this->Write(0, $pin);
    }

}