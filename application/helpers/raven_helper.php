<?php

// Instantiate a new client with a compatible DSN
if (defined('SENTRY_DSN')) {
    $raven_client = new Raven_Client(SENTRY_DSN, array(
        //'option_name' => 'value',
    ));

    // Install error handlers
    $error_handler = new Raven_ErrorHandler($raven_client);
    $error_handler->registerExceptionHandler();
    $error_handler->registerErrorHandler();
}

class Log {

    public static function d($message, $params=array()) {
        if (defined('SENTRY_DSN')) {
            $raven_client = new Raven_Client(SENTRY_DSN, array(
                //'option_name' => 'value',
            ));
            $raven_client->captureMessage($message, $params, Raven_Client::DEBUG);
        }
    }

    public static function i($message, $params=array()) {
        if (defined('SENTRY_DSN')) {
            $raven_client = new Raven_Client(SENTRY_DSN, array(
                //'option_name' => 'value',
            ));
            $raven_client->captureMessage($message, $params, Raven_Client::INFO);
        }
    }

    public static function w($message, $params=array()) {
        if (defined('SENTRY_DSN')) {
            $raven_client = new Raven_Client(SENTRY_DSN, array(
                //'option_name' => 'value',
            ));
            $raven_client->captureMessage($message, $params, Raven_Client::WARNING);
        }
    }

    public static function e($message, $params=array()) {
        if (defined('SENTRY_DSN')) {
            $raven_client = new Raven_Client(SENTRY_DSN, array(
                //'option_name' => 'value',
            ));
            $raven_client->captureMessage($message, $params, Raven_Client::ERROR);
        }
    }

    public static function f($message, $params=array()) {
        if (defined('SENTRY_DSN')) {
            $raven_client = new Raven_Client(SENTRY_DSN, array(
                //'option_name' => 'value',
            ));
            $raven_client->captureMessage($message, $params, Raven_Client::FATAL);
        }
    }
}