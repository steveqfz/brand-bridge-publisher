<?php

declare(strict_types=1);

return [
    'brand_key' => env('BRAND_BRIDGE_BRAND_KEY'),
    'connection' => env('BRAND_BRIDGE_DB_CONNECTION', 'mysql'),
    'publisher' => [
        'enabled' => env('BRAND_BRIDGE_PUBLISHER_ENABLED', false),
        'signing_key' => env('BRAND_BRIDGE_SIGNING_KEY'),
        'timestamp_tolerance_seconds' => 300,
        'rate_limit_per_minute' => 60,
    ],
];
