<?php

declare(strict_types=1);

namespace BrandBridge\Http\Middleware;

use BrandBridge\Http\Headers;
use BrandBridge\Services\HmacSignatureVerifier;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class VerifyBrandBridgeSignature
{
    public function __construct(
        private readonly HmacSignatureVerifier $verifier,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $signingKey = config('brand-bridge.publisher.signing_key');

        if (empty($signingKey)) {
            if (app()->environment('production')) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return response()->json([
                'error' => 'Brand Bridge signing key is not configured. Set BRAND_BRIDGE_SIGNING_KEY in your .env file.',
            ], 500);
        }

        $signature = $request->header(Headers::SIGNATURE);
        $timestamp = $request->header(Headers::TIMESTAMP);
        $version = $request->header(Headers::VERSION);

        if (!$signature || !$timestamp || !$version) {
            return response()->json([
                'error' => 'Missing required Brand Bridge headers.',
            ], 401);
        }

        $tolerance = (int) config('brand-bridge.publisher.timestamp_tolerance_seconds', 300);
        $requestTime = (int) $timestamp;
        $currentTime = time();

        if (abs($currentTime - $requestTime) > $tolerance) {
            return response()->json([
                'error' => 'Request timestamp is outside the acceptable tolerance window.',
            ], 401);
        }

        $isValid = $this->verifier->verify(
            method: $request->method(),
            path: $request->path(),
            timestamp: $timestamp,
            body: $request->getContent(),
            actualSignature: $signature,
            signingKey: $signingKey,
        );

        if (!$isValid) {
            return response()->json(['error' => 'Invalid signature.'], 401);
        }

        return $next($request);
    }
}
