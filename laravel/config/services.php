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

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'subscription_price_id' => env('STRIPE_SUBSCRIPTION_PRICE_ID'),
    ],

    'signaturit' => [
        'api_key' => env('SIGNATURIT_API_KEY', ''),
        'sandbox' => env('SIGNATURIT_SANDBOX', true),
        'webhook_secret' => env('SIGNATURIT_WEBHOOK_SECRET', ''),
    ],

    'google_docai' => [
        'project_id'   => env('GOOGLE_CLOUD_PROJECT', ''),
        'location'     => env('GOOGLE_CLOUD_LOCATION', 'eu'),
        'processor_id' => env('GOOGLE_DOCAI_PROCESSOR_ID', ''),
    ],

    'damage_valuation' => [
        'provider' => env('DAMAGE_VALUATION_PROVIDER', 'mock'),
        'dat' => [
            'api_key' => env('DAT_API_KEY', ''),
            'api_url' => env('DAT_API_URL', 'https://api.dat.de/rest/valuation/v1'),
        ],
        'audatex' => [
            'username' => env('AUDATEX_USERNAME', ''),
            'password' => env('AUDATEX_PASSWORD', ''),
            'url'      => env('AUDATEX_URL', 'https://services.audatex.es/api/v2'),
        ],
        'gt' => [
            'api_key' => env('GT_API_KEY', ''),
            'api_url' => env('GT_API_URL', 'https://api.gtestimate.es/v1'),
        ],
    ],

];
