<?php

return [
    /*
    |--------------------------------------------------------------------------
    | EHRM API Gateway Configuration
    |--------------------------------------------------------------------------
    */

    'base_url' => env('EHRM_BASE_URL', 'https://apigw.pu.go.id'),

    'api_key' => env('EHRM_API_KEY', ''),

    'email' => env('EHRM_EMAIL', ''),

    'password' => env('EHRM_PASSWORD', ''),

    // Token cache duration in seconds (default: 23 hours to be safe)
    'token_ttl' => env('EHRM_TOKEN_TTL', 82800),

    // Manual session token (for testing, leave empty to use auto-login)
    'manual_token' => env('EHRM_MANUAL_TOKEN', ''),
];
