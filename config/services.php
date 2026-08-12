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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI'),
    ],

    'pawapay' => [
        'base_url' => env('PAWAPAY_ENV', 'sandbox') === 'production'
            ? env('PAWAPAY_LIVE_URL', 'https://api.pawapay.io')
            : env('PAWAPAY_SANDBOX_URL', 'https://api.sandbox.pawapay.io'),
        'token' => env('PAWAPAY_API_TOKEN'),
        'callback_secret' => env('PAWAPAY_CALLBACK_SECRET'),
        'verify_callback_signature' => (bool) env('PAWAPAY_CALLBACK_VERIFY_SIGNATURE', false),
        'currency' => env('PAWAPAY_CURRENCY', 'XAF'),
        'country' => env('PAWAPAY_COUNTRY', 'COG'),
    ],

];
