<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Content Security Policy (CSP)
    |--------------------------------------------------------------------------
    */
    'csp' => [
        'report_only' => false, // Cambia a false para modo enforce
        'report_uri' => '/csp-report',
        'policy' => [
            'default-src' => "'self'",
            'script-src' => "'self' 'nonce' 'strict-dynamic'",
            'style-src' => "'self'",
            'img-src' => "'self' data:",
            'font-src' => "'self' data:",
            'connect-src' => "'self'",
            'media-src' => "'self'",
            'frame-src' => "'self'",
            'frame-ancestors' => "'self'",
            'object-src' => "'none'",
            'base-uri' => "'self'",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Strict-Transport-Security (HSTS)
    |--------------------------------------------------------------------------
    */
    'hsts' => [
        'max_age' => 31536000,
        'include_subdomains' => true,
        'preload' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | X-Frame-Options
    |--------------------------------------------------------------------------
    */
    'x_frame_options' => 'SAMEORIGIN',

    /*
    |--------------------------------------------------------------------------
    | X-Content-Type-Options
    |--------------------------------------------------------------------------
    */
    'x_content_type_options' => 'nosniff',

    /*
    |--------------------------------------------------------------------------
    | Referrer-Policy
    |--------------------------------------------------------------------------
    */
    'referrer_policy' => 'strict-origin-when-cross-origin',

    /*
    |--------------------------------------------------------------------------
    | Permissions-Policy
    |--------------------------------------------------------------------------
    */
    'permissions_policy' => [
        'accelerometer' => '()',
        'camera' => '()',
        'geolocation' => '()',
        'gyroscope' => '()',
        'magnetometer' => '()',
        'microphone' => '()',
        'payment' => '()',
        'usb' => '()',
    ],
];