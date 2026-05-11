<?php

declare(strict_types=1);

use BrandBridge\Contracts\Mappers\BettingLimitMapperInterface;
use BrandBridge\Contracts\Mappers\PlayerDetailsMapperInterface;
use BrandBridge\Contracts\Mappers\PlayerSnapshotMapperInterface;
use BrandBridge\Contracts\Mappers\PlayerTagMapperInterface;
use BrandBridge\Contracts\Mappers\SelfExclusionMapperInterface;
use BrandBridge\Contracts\Mappers\VipCommentMapperInterface;
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
    $tagMapper = new class implements PlayerTagMapperInterface {
        public function mapAll(string $sourcePlayerId): array { return []; }
    };
    $bettingLimitMapper = new class implements BettingLimitMapperInterface {
        public function map(string $sourcePlayerId): ?\BrandBridge\DTOs\V1\ResponsibleGaming\BettingLimitDTO { return null; }
    };
    $selfExclusionMapper = new class implements SelfExclusionMapperInterface {
        public function map(string $sourcePlayerId): ?\BrandBridge\DTOs\V1\ResponsibleGaming\SelfExclusionDTO { return null; }
    };
    $vipProfileMapper = new class implements VipProfileMapperInterface {
        public function map(string $sourcePlayerId): ?\BrandBridge\DTOs\V1\Vip\VipProfileDTO { return null; }
    };
    $vipGroupMapper = new class implements VipGroupMapperInterface {
        public function map(string $sourcePlayerId): ?\BrandBridge\DTOs\V1\Vip\VipGroupDTO { return null; }
    };
    $vipManagerMapper = new class implements VipManagerMapperInterface {
        public function map(string $sourcePlayerId): ?\BrandBridge\DTOs\V1\Vip\VipManagerDTO { return null; }
    };
    $vipCommentMapper = new class implements VipCommentMapperInterface {
        public function mapAll(string $sourcePlayerId): array { return []; }
    };
    $vipMilestoneMapper = new class implements VipMilestoneMapperInterface {
        public function mapAll(string $sourcePlayerId): array { return []; }
    };
    $vipPromiseMapper = new class implements VipPromiseMapperInterface {
        public function mapAll(string $sourcePlayerId): array { return []; }
    };
    $vipReminderMapper = new class implements VipReminderMapperInterface {
        public function mapAll(string $sourcePlayerId): array { return []; }
    };
    $vipContactMapper = new class implements VipContactMapperInterface {
        public function mapAll(string $sourcePlayerId): array { return []; }
    };

    return new OnboardingPayloadAssembler(
        $nullSnapshotMapper,
        $detailsMapper,
        $tagMapper,
        $bettingLimitMapper,
        $selfExclusionMapper,
        $vipProfileMapper,
        $vipGroupMapper,
        $vipManagerMapper,
        $vipCommentMapper,
        $vipMilestoneMapper,
        $vipPromiseMapper,
        $vipReminderMapper,
        $vipContactMapper,
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
    $tagMapper = new class implements PlayerTagMapperInterface {
        public function mapAll(string $sourcePlayerId): array { return []; }
    };
    $bettingLimitMapper = new class implements BettingLimitMapperInterface {
        public function map(string $sourcePlayerId): ?\BrandBridge\DTOs\V1\ResponsibleGaming\BettingLimitDTO { return null; }
    };
    $selfExclusionMapper = new class implements SelfExclusionMapperInterface {
        public function map(string $sourcePlayerId): ?\BrandBridge\DTOs\V1\ResponsibleGaming\SelfExclusionDTO { return null; }
    };
    $vipProfileMapper = new class implements VipProfileMapperInterface {
        public function map(string $sourcePlayerId): ?\BrandBridge\DTOs\V1\Vip\VipProfileDTO { return null; }
    };
    $vipGroupMapper = new class implements VipGroupMapperInterface {
        public function map(string $sourcePlayerId): ?\BrandBridge\DTOs\V1\Vip\VipGroupDTO { return null; }
    };
    $vipManagerMapper = new class implements VipManagerMapperInterface {
        public function map(string $sourcePlayerId): ?\BrandBridge\DTOs\V1\Vip\VipManagerDTO { return null; }
    };
    $vipCommentMapper = new class implements VipCommentMapperInterface {
        public function mapAll(string $sourcePlayerId): array { return []; }
    };
    $vipMilestoneMapper = new class implements VipMilestoneMapperInterface {
        public function mapAll(string $sourcePlayerId): array { return []; }
    };
    $vipPromiseMapper = new class implements VipPromiseMapperInterface {
        public function mapAll(string $sourcePlayerId): array { return []; }
    };
    $vipReminderMapper = new class implements VipReminderMapperInterface {
        public function mapAll(string $sourcePlayerId): array { return []; }
    };
    $vipContactMapper = new class implements VipContactMapperInterface {
        public function mapAll(string $sourcePlayerId): array { return []; }
    };

    return new OnboardingPayloadAssembler(
        $snapshotMapper,
        $detailsMapper,
        $tagMapper,
        $bettingLimitMapper,
        $selfExclusionMapper,
        $vipProfileMapper,
        $vipGroupMapper,
        $vipManagerMapper,
        $vipCommentMapper,
        $vipMilestoneMapper,
        $vipPromiseMapper,
        $vipReminderMapper,
        $vipContactMapper,
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
