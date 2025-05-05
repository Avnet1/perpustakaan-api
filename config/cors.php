<?php

return [

    'paths' => ['api/*', '*'], // Bisa disesuaikan sesuai kebutuhan

    'allowed_methods' => ['*'], // ['GET', 'POST', 'PUT', 'DELETE']

    'allowed_origins' => ['*'], // ['https://yourdomain.com']

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
