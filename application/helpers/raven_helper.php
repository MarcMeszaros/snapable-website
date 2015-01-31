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

    private static function writeToConsole($message) {
        $output = fopen('php://stdout', 'w');
        ob_start();
        fwrite($output, $message."\n");
        ob_end_flush();
    }

    public static function d($message, $params=array()) {
        self::writeToConsole($message);
    }

    public static function i($message, $params=array()) {
        if (defined('SENTRY_DSN')) {
            $raven_client = new Raven_Client(SENTRY_DSN, array(
                //'option_name' => 'value',
            ));
            $raven_client->captureMessage($message, $params, Raven_Client::INFO);
        }
        self::writeToConsole($message);
    }

    public static function w($message, $params=array()) {
        if (defined('SENTRY_DSN')) {
            $raven_client = new Raven_Client(SENTRY_DSN, array(
                //'option_name' => 'value',
            ));
            $raven_client->captureMessage($message, $params, Raven_Client::WARNING);
        }
        self::writeToConsole($message);
    }

    public static function e($message, $params=array()) {
        if (defined('SENTRY_DSN')) {
            $raven_client = new Raven_Client(SENTRY_DSN, array(
                //'option_name' => 'value',
            ));
            $raven_client->captureMessage($message, $params, Raven_Client::ERROR);
        }
        self::writeToConsole($message);
    }

    public static function f($message, $params=array()) {
        if (defined('SENTRY_DSN')) {
            $raven_client = new Raven_Client(SENTRY_DSN, array(
                //'option_name' => 'value',
            ));
            $raven_client->captureMessage($message, $params, Raven_Client::FATAL);
        }
        self::writeToConsole($message);
    }
}