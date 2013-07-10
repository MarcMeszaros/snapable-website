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
