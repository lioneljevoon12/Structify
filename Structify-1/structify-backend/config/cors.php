<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Di production isi CORS_ALLOWED_ORIGINS dengan domain frontend, dipisah koma.
    // Default-nya origin development:
    // React Vite (5173), React alt (3000), Flutter web (8081), Android emulator (10.0.2.2).
    'allowed_origins' => array_values(array_filter(array_map('trim', explode(',', env(
        'CORS_ALLOWED_ORIGINS',
        'http://localhost:5173,http://localhost:3000,http://127.0.0.1:5173,http://localhost:8081,http://10.0.2.2:8000'
    ))))),

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