<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . '/../libs/tcpdf/tcpdf.php');
require_once(dirname(__FILE__) . '/../libs/fpdi/fpdi.php');

class SnapPdf extends FPDI {

    function __construct() {
        parent::__construct('L', 'mm', 'USLETTER');
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

}