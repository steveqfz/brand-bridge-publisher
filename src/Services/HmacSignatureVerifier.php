<?php

declare(strict_types=1);

namespace BrandBridge\Services;

final class HmacSignatureVerifier
{
    public function verify(
        string $method,
        string $path,
        string $timestamp,
        string $body,
        string $actualSignature,
        string $signingKey,
    ): bool {
        $payload = strtoupper($method) . "\n"
            . $path . "\n"
            . $timestamp . "\n"
            . hash('sha256', $body);

        $expected = hash_hmac('sha256', $payload, $signingKey);

        return hash_equals($expected, $actualSignature);
    }
}
