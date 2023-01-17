<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => '1049040768958-7pr33rjk3r05sdhi8d451rkd8fsc7p2d.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'https://hotdeal.id/api/login/google/callback',
    ],

    'facebook' => [
        'client_id' => '379978210247237',
        'client_secret' => '0981fa2492c7761da2163a0aa2c9df8c',
        'redirect' => 'https://hotdeal.id/api/login/facebook/callback',
    ],

];
