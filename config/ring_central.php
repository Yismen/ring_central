<?php

return [
    'db_prefix' => env('RC_DB_PREFIX', 'rc_'),
    'middlewares' => [
        'api' => 'api'
    ],
    'connection' => [
        'name' => 'ring_central',
        'host' => env('RC_DB_HOST'),
        'database' => env('RC_DB_DATABASE'),
        'username' => env('RC_DB_USERNAME'),
        'password' => env('RC_DB_PASSWORD'),
        'driver' => env('RC_DB_DRIVER', 'sqlsrv'),
        'url' => env('RC_DB_URL', null),
        'port' => env('RC_DB_PORT', '1433'),
        'prefix_indexes' => true
    ],
];
