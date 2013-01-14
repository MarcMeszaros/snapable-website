<?php

    require_once(APPPATH.'libs/raven-php/lib/Raven/Autoloader.php');
    Raven_Autoloader::register();

    // Instantiate a new client with a compatible DSN
    $raven_client = new Raven_Client(SENTRY_DSN);

    // Install error handlers
    $error_handler = new Raven_ErrorHandler($raven_client);
    $error_handler->registerExceptionHandler();
    $error_handler->registerErrorHandler();
