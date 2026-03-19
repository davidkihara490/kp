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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'mpesa' => [
        'consumer_key' => env('MPESA_CONSUMER_KEY'),
        'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
        'shortcode' => env('MPESA_SHORTCODE'),
        'passkey' => env('MPESA_PASSKEY'),
        'callback_url' => env('MPESA_CALLBACK_URL'),
        'environment' => env('MPESA_ENVIRONMENT', 'sandbox'),
    ],
    'mobitech' => [
        'api_key' => env('MOBITECH_API_KEY'),
        'sender_name' => env('MOBITECH_SENDER', 'FULL_CIRCLE'),
        'api_url' => env('MOBITECH_API_URL', 'https://app.mobitechtechnologies.com/sms/sendsms'),
        'access_key' => env('ONFONMEDIA_ACCESS_KEY'),
    ],

    'onfonmedia' => [
        'api_key' => env('ONFONMEDIA_API_KEY'),
        'sender_name' => env('ONFONMEDIA_CLIENT_ID'),
        'api_url' => env('ONFONMEDIA_API_URL'),
    ],

    'whatsapp' => [
        'phone' => env('WHATSAPP_PHONE', '254700130759'),
        'message' => env('WHATSAPP_MESSAGE', 'Hello, I need more information about your services.'),
    ],

];
