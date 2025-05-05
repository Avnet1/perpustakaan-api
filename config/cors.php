<?php

return [
    'paths' => ['api/*', 'v1/*', 'sanctum/csrf-cookie'],  // Sesuaikan jika perlu

    'allowed_methods' => ['*'],  // Mengizinkan semua metode HTTP

    'allowed_origins' => ['*'],  // Mengizinkan semua asal, bisa diganti dengan domain spesifik
    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],  // Mengizinkan semua headers

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,  // Mengizinkan kredensial, jika diperlukan
];
