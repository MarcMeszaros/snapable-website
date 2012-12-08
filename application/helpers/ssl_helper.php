<?php

    /**
     * Checks the current request to see if it is over SSL and redirect
     * to an SSL version of required.
     */
    function require_https() {
        // if the redirect ssl define is not set, set it
        if(!defined('SSL_REDIRECT')) { define('SSL_REDIRECT', true); }

        // redirect if required
        if( (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') && SSL_REDIRECT == true)
        {
            header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            exit();
        }
    }

    function require_http() {
        // redirect if required
        if( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'))
        {
            header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            exit();
        }
    }