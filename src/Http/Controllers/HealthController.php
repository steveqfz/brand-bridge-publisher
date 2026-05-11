<?php

declare(strict_types=1);

namespace BrandBridge\Http\Controllers;

use Illuminate\Http\JsonResponse;

final class HealthController
{
    public function show(): JsonResponse
    {
        return new JsonResponse([
            'status' => 'ok',
            'version' => 'v1',
            'brand' => config('brand-bridge.brand_key'),
        ]);
    }
}
