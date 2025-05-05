<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | The allowed origins and headers for the requests can be defined here.
    |
    */

    'paths' => [
        'api/*',  // Path untuk API
        'v1/*',   // Path tambahan jika diperlukan
    ],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => ['*'],

    'allowed_headers' => ['*'],  // Mengizinkan semua headers

    'exposed_headers' => [],

    'max_age' => 3600,  // Durasi cache untuk preflight request

    'supports_credentials' => true, // Membolehkan kredensial (cookies, session, atau authorization header)
];
