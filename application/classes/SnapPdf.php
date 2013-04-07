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