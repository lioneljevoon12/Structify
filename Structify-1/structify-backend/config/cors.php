<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173',      // React Vite dev
        'http://localhost:3000',      // React alt port
        'http://127.0.0.1:5173',     // React Vite alt
        'http://localhost:8081',      // Flutter web dev
        'http://10.0.2.2:8000',      // Flutter Android emulator akses localhost
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'Accept',
        'Origin',
    ],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];