<?php

declare(strict_types=1);

use BrandBridge\Contracts\EligibilityCheckerInterface;
use BrandBridge\DTOs\V1\EligibilityResult;

function eligibilitySignedHeaders(string $path, string $key): array
{
    $timestamp = (string) time();
    $payload = "GET\n" . $path . "\n" . $timestamp . "\n" . hash('sha256', '');
    $signature = hash_hmac('sha256', $payload, $key);

    return [
        'X-Brand-Bridge-Signature' => $signature,
        'X-Brand-Bridge-Timestamp' => $timestamp,
        'X-Brand-Bridge-Version' => 'v1',
    ];
}

it('returns eligible result for a valid player', function () {
    $this->app->bind(EligibilityCheckerInterface::class, function () {
        return new class implements EligibilityCheckerInterface {
            public function check(string $sourcePlayerId): EligibilityResult
            {
                return new EligibilityResult(
                    eligible: true,
                    reasons: [],
                    checkedAt: new DateTimeImmutable('2026-01-15T10:00:00+00:00'),
                );
            }
        };
    });

    $path = 'api/cross-brand/bridge/eligibility/PLR-001';
    $key = config('brand-bridge.publisher.signing_key');
    $headers = eligibilitySignedHeaders($path, $key);

    $response = $this->withHeaders($headers)
        ->getJson('/api/cross-brand/bridge/eligibility/PLR-001');

    $response->assertOk();
    $response->assertJsonFragment(['eligible' => true]);
});

it('returns ineligible result with reasons', function () {
    $this->app->bind(EligibilityCheckerInterface::class, function () {
        return new class implements EligibilityCheckerInterface {
            public function check(string $sourcePlayerId): EligibilityResult
            {
                return new EligibilityResult(
                    eligible: false,
                    reasons: ['Player is self-excluded'],
                    checkedAt: new DateTimeImmutable('2026-01-15T10:00:00+00:00'),
                );
            }
        };
    });

    $path = 'api/cross-brand/bridge/eligibility/PLR-002';
    $key = config('brand-bridge.publisher.signing_key');
    $headers = eligibilitySignedHeaders($path, $key);

    $response = $this->withHeaders($headers)
        ->getJson('/api/cross-brand/bridge/eligibility/PLR-002');

    $response->assertOk();
    $response->assertJsonFragment(['eligible' => false]);
    $response->assertJsonFragment(['reasons' => ['Player is self-excluded']]);
});
