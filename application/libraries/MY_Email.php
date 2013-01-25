<?php

class MY_Email extends CI_Email {

    /**
     * Add a Header Item
     *
     * @access  public
     * @param   string
     * @param   string
     * @return  void
     */
    public function set_header($header, $value)
    {
        $this->_set_header($header, $value);
    }

}
