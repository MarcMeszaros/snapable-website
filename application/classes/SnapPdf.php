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

    /**
     * Setup the title
     */
    public function title($title='') {
        // setup the title
        $title = trim($title);
        $this->SetTitle($title);
        $this->SetFont('vegur');
        $this->SetFontSize(10.0);
        $this->SetTextColor(0,0,0);

        // truncate the title if required
        if(strlen($title) > 55) {
            $title = substr($title, 0, 55) . '...';
        }

        $html = '
        <ol>
            <li>Search for <b>Snapable</b> in the app store and download the app to your phone.</li><br>
            <li>Open Snapable, allow it access to your location (so it can find the event).</li><br>
            <li>Select <b>"'.$title.'"</b> in the event list and enter the PIN below for access (if required).</li><br>
            <li>Click on the camera icon and start snappin’</li>
        </ol>';
        // width, height, x, y, html
        $this->writeHTMLCell(90, 48, -3, 34, $html); // top left quarter
        $this->writeHTMLCell(90, 48, 136.5, 34, $html); // top right quarter
        $this->writeHTMLCell(90, 48, -3, 142, $html); // bottom left quarter
        $this->writeHTMLCell(90, 48, 136.5, 142, $html); // bottom right quarter
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
        $this->MultiCell(90, 0, $baseEventUrl . $url, 0, '', false, 1, 4.5, 96);

        // top right quarter
        $this->MultiCell(90, 0, $baseEventUrl . $url, 0, '', false, 1, 143.75, 96);

        // bottom left quarter
        $this->MultiCell(90, 0, $baseEventUrl . $url, 0, '', false, 1, 4.5, 204);

        // bottom right quarter
        $this->MultiCell(90, 0, $baseEventUrl . $url, 0, '', false, 1, 143.75, 204);
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