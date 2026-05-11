<?php

declare(strict_types=1);

use BrandBridge\Contracts\Mappers\PlayerDetailsMapperInterface;
use BrandBridge\Contracts\Mappers\PlayerSnapshotMapperInterface;
use BrandBridge\Contracts\Mappers\PlayerTagMapperInterface;
use BrandBridge\Contracts\Mappers\VipContactMapperInterface;
use BrandBridge\Contracts\Mappers\VipGroupMapperInterface;
use BrandBridge\Contracts\Mappers\VipManagerMapperInterface;
use BrandBridge\Contracts\Mappers\VipMilestoneMapperInterface;
use BrandBridge\Contracts\Mappers\VipProfileMapperInterface;
use BrandBridge\Contracts\Mappers\VipPromiseMapperInterface;
use BrandBridge\Contracts\Mappers\VipReminderMapperInterface;
use BrandBridge\DTOs\V1\PlayerDetailsDTO;
use BrandBridge\DTOs\V1\PlayerSnapshot;
use BrandBridge\Enums\BrandKey;
use BrandBridge\Enums\PlayerStatus;

function snapshotSignedHeaders(string $path, string $key): array
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

function bindNullMappers(\Illuminate\Foundation\Application $app): void
{
    $app->bind(PlayerSnapshotMapperInterface::class, fn () => new class implements PlayerSnapshotMapperInterface {
        public function map(string $sourcePlayerId): ?PlayerSnapshot { return null; }
    });
    $app->bind(PlayerDetailsMapperInterface::class, fn () => new class implements PlayerDetailsMapperInterface {
        public function map(string $sourcePlayerId): ?PlayerDetailsDTO { return null; }
    });
    $app->bind(PlayerTagMapperInterface::class, fn () => new class implements PlayerTagMapperInterface {
        public function mapAll(string $sourcePlayerId): array { return []; }
    });
}

function bindValidMappers(\Illuminate\Foundation\Application $app): void
{
    $app->bind(PlayerSnapshotMapperInterface::class, fn () => new class implements PlayerSnapshotMapperInterface {
        public function map(string $sourcePlayerId): ?PlayerSnapshot
        {
            return new PlayerSnapshot(
                sourcePlayerId: $sourcePlayerId,
                sourceBrand: BrandKey::Vegastars,
                email: 'test@example.com',
                username: 'testplayer',
                firstName: 'John',
                lastName: 'Doe',
                dateOfBirth: new DateTimeImmutable('1990-01-01'),
                country: 'GB',
                phoneNumber: '+44123456789',
                registeredAt: new DateTimeImmutable('2023-01-01T00:00:00+00:00'),
                lastLoginAt: new DateTimeImmutable('2026-01-14T18:00:00+00:00'),
                kycReference: 'KYC-123',
                currency: 'GBP',
                language: 'en',
            );
        }
    });
    $app->bind(PlayerDetailsMapperInterface::class, fn () => new class implements PlayerDetailsMapperInterface {
        public function map(string $sourcePlayerId): ?PlayerDetailsDTO
        {
            return new PlayerDetailsDTO(
                gender: 'male',
                address: '123 Test St',
                city: 'London',
                postalCode: 'SW1A 1AA',
                region: 'Greater London',
                status: PlayerStatus::Active,
                totalDeposits: 50000.00,
                totalWithdrawals: 20000.00,
                netRevenue: 30000.00,
                lastDepositAt: new DateTimeImmutable('2026-01-10T12:00:00+00:00'),
                vipSince: new DateTimeImmutable('2024-06-01T00:00:00+00:00'),
            );
        }
    });
    $app->bind(PlayerTagMapperInterface::class, fn () => new class implements PlayerTagMapperInterface {
        public function mapAll(string $sourcePlayerId): array { return []; }
    });
}

it('returns 404 when player not found', function () {
    bindNullMappers($this->app);

    $path = 'api/cross-brand/bridge/players/PLR-UNKNOWN/onboarding-payload';
    $key = config('brand-bridge.publisher.signing_key');
    $headers = snapshotSignedHeaders($path, $key);

    $response = $this->withHeaders($headers)
        ->getJson('/api/cross-brand/bridge/players/PLR-UNKNOWN/onboarding-payload');

    $response->assertNotFound();
    $response->assertJson(['error' => 'player_not_found']);
});

it('returns properly shaped JSON for valid player', function () {
    bindValidMappers($this->app);

    $path = 'api/cross-brand/bridge/players/PLR-001/onboarding-payload';
    $key = config('brand-bridge.publisher.signing_key');
    $headers = snapshotSignedHeaders($path, $key);

    $response = $this->withHeaders($headers)
        ->getJson('/api/cross-brand/bridge/players/PLR-001/onboarding-payload');

    $response->assertOk();
    $response->assertJsonStructure([
        'version',
        'data' => [
            'version',
            'source_brand',
            'source_player_id',
            'player',
            'details',
            'tags',
            'snapshot_taken_at',
        ],
        'snapshot_taken_at',
    ]);
    $response->assertJsonFragment(['version' => 'v1']);
});
