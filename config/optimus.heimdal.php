<?php

use \App\Exceptions\Formatters;
use Symfony\Component\HttpKernel\Exception as SymfonyException;

return [
    'add_cors_headers' => TRUE,

    // Has to be in prioritized order, e.g. highest priority first.
    'formatters' => [
        Illuminate\Validation\ValidationException::class => Formatters\ValidationExceptionFormatter::class,
        SymfonyException\HttpException::class => Formatters\HttpExceptionFormatter::class,
        Exception::class => Formatters\ExceptionFormatter::class,
    ],

    'response_factory' => \Optimus\Heimdal\ResponseFactory::class,

    'reporters' => [
        /*'sentry' => [
            'class'  => \Optimus\Heimdal\Reporters\SentryReporter::class,
            'config' => [
                'dsn' => '',
                // For extra options see https://docs.sentry.io/clients/php/config/
                // php version and environment are automatically added.
                'sentry_options' => []
            ]
        ]*/
    ],

    'server_error_production' => 'An error occurred.',
];
