<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Credentials
    |--------------------------------------------------------------------------
    |
    | Path to your Firebase service account JSON file
    |
    */
    'credentials' => [
        'file' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase/service-account.json')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Database URL
    |--------------------------------------------------------------------------
    */
    'database_url' => env('FIREBASE_DATABASE_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Firebase Project ID
    |--------------------------------------------------------------------------
    */
    'project_id' => env('FIREBASE_PROJECT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Firebase Storage Bucket
    |--------------------------------------------------------------------------
    */
    'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', ''),

    /*
    |--------------------------------------------------------------------------
    | OTP Configuration
    |--------------------------------------------------------------------------
    */
    'otp' => [
        'length' => 6,
        'expiry_minutes' => 5,
        'max_attempts' => 3,
        'rate_limit_per_day' => 10, // Max OTP requests per phone per day
        'resend_delay_seconds' => 30, // Minimum time between OTP requests (30 seconds for development)
    ],

    /*
    |--------------------------------------------------------------------------
    | PIN Configuration
    |--------------------------------------------------------------------------
    */
    'pin' => [
        'length' => 4,
        'max_attempts' => 5,
        'lockout_minutes' => 15,
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Web Client Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for client-side Firebase SDK (JavaScript)
    | Used for Firebase Phone Authentication from browser
    |
    */
    'web' => [
        'api_key' => env('FIREBASE_WEB_API_KEY', ''),
        'auth_domain' => env('FIREBASE_AUTH_DOMAIN', env('FIREBASE_PROJECT_ID') . '.firebaseapp.com'),
        'project_id' => env('FIREBASE_PROJECT_ID', ''),
        'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', ''),
        'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID', ''),
        'app_id' => env('FIREBASE_APP_ID', ''),
    ],
];
