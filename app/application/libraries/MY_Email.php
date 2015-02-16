<?php

class MY_Email extends CI_Email {

    /**
     * Override the parent constructor and add some defaults.
     */
    public function __construct($config = array())
    {
        // parent constructor
        parent::__construct($config);
    }

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
