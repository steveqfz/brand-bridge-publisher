<?php

declare(strict_types=1);

use BrandBridge\Contracts\Mappers\PlayerDetailsMapperInterface;
use BrandBridge\Contracts\Mappers\PlayerSnapshotMapperInterface;
use BrandBridge\DTOs\V1\PlayerDetailsDTO;
use BrandBridge\DTOs\V1\PlayerSnapshot;
use BrandBridge\Enums\BrandKey;
use BrandBridge\Enums\PlayerStatus;
use BrandBridge\Exceptions\PlayerNotFoundException;
use BrandBridge\Services\OnboardingPayloadAssembler;

function makeAssemblerWithNullPlayer(): OnboardingPayloadAssembler
{
    $nullSnapshotMapper = new class implements PlayerSnapshotMapperInterface {
        public function map(string $sourcePlayerId): ?PlayerSnapshot { return null; }
    };
    $detailsMapper = new class implements PlayerDetailsMapperInterface {
        public function map(string $sourcePlayerId): ?PlayerDetailsDTO { return null; }
    };

    return new OnboardingPayloadAssembler(
        $nullSnapshotMapper,
        $detailsMapper,
    );
}

function makeAssemblerWithValidPlayer(): OnboardingPayloadAssembler
{
    $snapshotMapper = new class implements PlayerSnapshotMapperInterface {
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
    };
    $detailsMapper = new class implements PlayerDetailsMapperInterface {
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
    };

    return new OnboardingPayloadAssembler(
        $snapshotMapper,
        $detailsMapper,
    );
}

it('throws PlayerNotFoundException when player mapper returns null', function () {
    $assembler = makeAssemblerWithNullPlayer();

    $assembler->assemble('PLR-NONEXISTENT');
})->throws(PlayerNotFoundException::class);

it('returns a valid OnboardingPayload when all mappers return data', function () {
    $assembler = makeAssemblerWithValidPlayer();

    $payload = $assembler->assemble('PLR-001');

    expect($payload->sourcePlayerId)->toBe('PLR-001');
    expect($payload->player->email)->toBe('test@example.com');
    expect($payload->details->status)->toBe(PlayerStatus::Active);
    expect($payload->version->value)->toBe('v1');
    expect($payload->sourceBrand)->toBe(BrandKey::Vegastars);
});
