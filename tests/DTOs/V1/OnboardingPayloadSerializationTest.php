<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\OnboardingPayload;
use BrandBridge\DTOs\V1\PlayerDetailsDTO;
use BrandBridge\DTOs\V1\PlayerSnapshot;
use BrandBridge\DTOs\V1\PlayerTagDTO;
use BrandBridge\Enums\BrandKey;
use BrandBridge\Enums\PayloadVersion;
use BrandBridge\Enums\PlayerStatus;

function buildOnboardingPayload(): OnboardingPayload
{
    $now = new DateTimeImmutable('2026-01-15T10:00:00+00:00');

    return new OnboardingPayload(
        version: PayloadVersion::V1,
        sourceBrand: BrandKey::Vegastars,
        sourcePlayerId: 'PLR-001',
        player: new PlayerSnapshot(
            sourcePlayerId: 'PLR-001',
            sourceBrand: BrandKey::Vegastars,
            email: 'test@example.com',
            username: 'testplayer',
            firstName: 'John',
            lastName: 'Doe',
            dateOfBirth: new DateTimeImmutable('1990-05-20'),
            country: 'GB',
            phoneNumber: '+44123456789',
            registeredAt: new DateTimeImmutable('2023-01-01T00:00:00+00:00'),
            lastLoginAt: new DateTimeImmutable('2026-01-14T18:00:00+00:00'),
            kycReference: 'KYC-123',
            currency: 'GBP',
            language: 'en',
        ),
        details: new PlayerDetailsDTO(
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
        ),
        tags: [
            new PlayerTagDTO(name: 'segment', value: 'high-roller', assignedAt: $now),
        ],
        snapshotTakenAt: $now,
    );
}

it('round-trips through toArray/fromArray', function () {
    $original = buildOnboardingPayload();
    $array = $original->toArray();
    $restored = OnboardingPayload::fromArray($array);

    expect($restored->version)->toBe($original->version);
    expect($restored->sourceBrand)->toBe($original->sourceBrand);
    expect($restored->sourcePlayerId)->toBe($original->sourcePlayerId);
    expect($restored->player->email)->toBe($original->player->email);
    expect($restored->details->status)->toBe($original->details->status);
    expect($restored->tags)->toHaveCount(1);
    expect($restored->tags[0]->name)->toBe('segment');
});

it('produces a deterministic fingerprint', function () {
    $payload = buildOnboardingPayload();

    $fp1 = $payload->fingerprint();
    $fp2 = $payload->fingerprint();

    expect($fp1)->toBe($fp2);
    expect(strlen($fp1))->toBe(64);
});

it('fingerprint changes when a field changes', function () {
    $payload1 = buildOnboardingPayload();

    $payload2 = new OnboardingPayload(
        version: $payload1->version,
        sourceBrand: $payload1->sourceBrand,
        sourcePlayerId: 'PLR-DIFFERENT',
        player: $payload1->player,
        details: $payload1->details,
        tags: $payload1->tags,
        snapshotTakenAt: $payload1->snapshotTakenAt,
    );

    expect($payload1->fingerprint())->not->toBe($payload2->fingerprint());
});
