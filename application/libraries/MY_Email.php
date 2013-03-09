<?php

class MY_Email extends CI_Email {

    /**
     * Override the parent constructor and add some defaults.
     */
    public function __construct($config = array())
    {
        // parent constructor
        parent::__construct($config);

        // disable the subscribe link sendgrid automatically adds
        $email_headers = array(
            'filters' => array(
                'subscriptiontrack' => array(
                    'settings' => array(
                        'enable' => 0,
                    ),
                ),
            ),
        );

        // set the header
        $this->_set_header('X-SMTPAPI', json_encode($email_headers));
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
