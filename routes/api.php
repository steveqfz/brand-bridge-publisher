<?php

declare(strict_types=1);

use BrandBridge\Http\Controllers\EligibilityController;
use BrandBridge\Http\Controllers\HealthController;
use BrandBridge\Http\Controllers\PlayerSnapshotController;
use BrandBridge\Http\Middleware\VerifyBrandBridgeSignature;
use Illuminate\Support\Facades\Route;

Route::prefix(\BrandBridge\Http\ApiPaths::PREFIX)->group(function () {
    Route::get('/health', [HealthController::class, 'show'])
        ->name('brand-bridge.health');

    Route::middleware([
        VerifyBrandBridgeSignature::class,
        'throttle:brand-bridge',
    ])->group(function () {
        Route::get('/eligibility/{sourcePlayerId}', [EligibilityController::class, 'show'])
            ->name('brand-bridge.eligibility');
        Route::get('/players/{sourcePlayerId}/onboarding-payload',
            [PlayerSnapshotController::class, 'show'])
            ->name('brand-bridge.onboarding-payload');
    });
});
