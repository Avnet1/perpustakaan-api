<?php

return [

    /*
    |----------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |----------------------------------------------------------------------
    */

    'paths' => [
        'api/*',
        'v1/*',
    ],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        'http://localhost:3000',
        'http://192.168.20.86:3000',
        'http://dev-sikeu-unimed.avnet.id'
    ],  // Harus sesuai dengan frontend yang memanggil API

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],  // Mengizinkan semua headers

    'exposed_headers' => [],

    'max_age' => 3600,  // Durasi cache untuk preflight request

    'supports_credentials' => true, // Membolehkan kredensial (cookies, session, atau authorization header)

];
