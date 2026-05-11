<?php

declare(strict_types=1);

use BrandBridge\Contracts\EligibilityCheckerInterface;
use BrandBridge\DTOs\V1\EligibilityResult;

function generateSignature(string $method, string $path, string $timestamp, string $body, string $key): string
{
    $payload = strtoupper($method) . "\n"
        . $path . "\n"
        . $timestamp . "\n"
        . hash('sha256', $body);

    return hash_hmac('sha256', $payload, $key);
}

function signedHeaders(string $method, string $path, string $key, string $body = ''): array
{
    $timestamp = (string) time();
    $signature = generateSignature($method, $path, $timestamp, $body, $key);

    return [
        'X-Brand-Bridge-Signature' => $signature,
        'X-Brand-Bridge-Timestamp' => $timestamp,
        'X-Brand-Bridge-Version' => 'v1',
    ];
}

beforeEach(function () {
    $this->app->bind(EligibilityCheckerInterface::class, function () {
        return new class implements EligibilityCheckerInterface {
            public function check(string $sourcePlayerId): EligibilityResult
            {
                return new EligibilityResult(
                    eligible: true,
                    reasons: [],
                    checkedAt: new DateTimeImmutable(),
                );
            }
        };
    });
});

it('passes with a valid signature', function () {
    $path = 'api/cross-brand/bridge/eligibility/PLR-001';
    $key = config('brand-bridge.publisher.signing_key');
    $headers = signedHeaders('GET', $path, $key);

    $response = $this->withHeaders($headers)
        ->getJson('/api/cross-brand/bridge/eligibility/PLR-001');

    $response->assertOk();
});

it('returns 401 when headers are missing', function () {
    $response = $this->getJson('/api/cross-brand/bridge/eligibility/PLR-001');

    $response->assertUnauthorized();
});

it('returns 401 with wrong signature', function () {
    $headers = [
        'X-Brand-Bridge-Signature' => 'invalid-signature',
        'X-Brand-Bridge-Timestamp' => (string) time(),
        'X-Brand-Bridge-Version' => 'v1',
    ];

    $response = $this->withHeaders($headers)
        ->getJson('/api/cross-brand/bridge/eligibility/PLR-001');

    $response->assertUnauthorized();
});

it('returns 401 for expired timestamp (replay attack)', function () {
    $path = 'api/cross-brand/bridge/eligibility/PLR-001';
    $key = config('brand-bridge.publisher.signing_key');
    $expiredTimestamp = (string) (time() - 600);
    $signature = generateSignature('GET', $path, $expiredTimestamp, '', $key);

    $headers = [
        'X-Brand-Bridge-Signature' => $signature,
        'X-Brand-Bridge-Timestamp' => $expiredTimestamp,
        'X-Brand-Bridge-Version' => 'v1',
    ];

    $response = $this->withHeaders($headers)
        ->getJson('/api/cross-brand/bridge/eligibility/PLR-001');

    $response->assertUnauthorized();
});
