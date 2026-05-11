<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\OnboardingPayload;
use BrandBridge\DTOs\V1\PlayerDetailsDTO;
use BrandBridge\DTOs\V1\PlayerSnapshot;
use BrandBridge\DTOs\V1\PlayerTagDTO;
use BrandBridge\DTOs\V1\ResponsibleGaming\BettingLimitDTO;
use BrandBridge\DTOs\V1\ResponsibleGaming\SelfExclusionDTO;
use BrandBridge\DTOs\V1\Vip\VipCommentDTO;
use BrandBridge\DTOs\V1\Vip\VipContactDTO;
use BrandBridge\DTOs\V1\Vip\VipGroupDTO;
use BrandBridge\DTOs\V1\Vip\VipGroupSettingsDTO;
use BrandBridge\DTOs\V1\Vip\VipManagerDTO;
use BrandBridge\DTOs\V1\Vip\VipManagerLeaderDTO;
use BrandBridge\DTOs\V1\Vip\VipMilestoneDTO;
use BrandBridge\DTOs\V1\Vip\VipProfileDTO;
use BrandBridge\DTOs\V1\Vip\VipPromiseDTO;
use BrandBridge\DTOs\V1\Vip\VipReminderDTO;
use BrandBridge\Enums\BrandKey;
use BrandBridge\Enums\PayloadVersion;
use BrandBridge\Enums\PlayerStatus;
use BrandBridge\Enums\VipTier;

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
        bettingLimit: new BettingLimitDTO(
            limitType: 'daily',
            amount: 1000.00,
            currency: 'GBP',
            effectiveFrom: $now,
            effectiveUntil: null,
        ),
        selfExclusion: null,
        vipProfile: new VipProfileDTO(
            tier: VipTier::Gold,
            points: 5000,
            lifetimePoints: 25000,
            tierAchievedAt: new DateTimeImmutable('2025-01-01T00:00:00+00:00'),
            tierExpiresAt: new DateTimeImmutable('2027-01-01T00:00:00+00:00'),
            isRetained: true,
        ),
        vipGroup: new VipGroupDTO(
            groupId: 'GRP-1',
            name: 'Gold VIP Group',
            description: 'VIP group for gold tier',
            settings: new VipGroupSettingsDTO(
                maxMembers: 100,
                autoAssign: true,
                criteria: 'tier >= gold',
            ),
        ),
        vipManager: new VipManagerDTO(
            managerId: 'MGR-1',
            name: 'Jane Smith',
            email: 'jane@company.com',
            leader: new VipManagerLeaderDTO(
                leaderId: 'LDR-1',
                name: 'Bob Leader',
                email: 'bob@company.com',
            ),
            assignedAt: $now,
        ),
        vipComments: [
            new VipCommentDTO(
                commentId: 'CMT-1',
                authorName: 'Jane Smith',
                content: 'Player upgraded to gold',
                createdAt: $now,
            ),
        ],
        vipMilestones: [
            new VipMilestoneDTO(
                milestoneId: 'MS-1',
                title: '10K deposits',
                description: 'Reached 10K in deposits',
                achievedAt: $now,
                targetValue: 10000.0,
                currentValue: 10000.0,
            ),
        ],
        vipPromises: [
            new VipPromiseDTO(
                promiseId: 'PRM-1',
                title: 'Birthday bonus',
                description: 'Promised birthday bonus',
                promisedAt: $now,
                fulfilledAt: null,
                status: 'pending',
            ),
        ],
        vipReminders: [
            new VipReminderDTO(
                reminderId: 'REM-1',
                title: 'Follow up call',
                description: 'Call player next week',
                dueAt: new DateTimeImmutable('2026-01-22T10:00:00+00:00'),
                completedAt: null,
                isCompleted: false,
            ),
        ],
        vipContacts: [
            new VipContactDTO(
                contactId: 'CON-1',
                type: 'email',
                value: 'test@example.com',
                isPrimary: true,
                createdAt: $now,
            ),
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
    expect($restored->bettingLimit)->not->toBeNull();
    expect($restored->bettingLimit->amount)->toBe(1000.00);
    expect($restored->selfExclusion)->toBeNull();
    expect($restored->vipProfile->tier)->toBe(VipTier::Gold);
    expect($restored->vipGroup->settings)->not->toBeNull();
    expect($restored->vipManager->leader)->not->toBeNull();
    expect($restored->vipComments)->toHaveCount(1);
    expect($restored->vipMilestones)->toHaveCount(1);
    expect($restored->vipPromises)->toHaveCount(1);
    expect($restored->vipReminders)->toHaveCount(1);
    expect($restored->vipContacts)->toHaveCount(1);
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
        bettingLimit: $payload1->bettingLimit,
        selfExclusion: $payload1->selfExclusion,
        vipProfile: $payload1->vipProfile,
        vipGroup: $payload1->vipGroup,
        vipManager: $payload1->vipManager,
        vipComments: $payload1->vipComments,
        vipMilestones: $payload1->vipMilestones,
        vipPromises: $payload1->vipPromises,
        vipReminders: $payload1->vipReminders,
        vipContacts: $payload1->vipContacts,
        snapshotTakenAt: $payload1->snapshotTakenAt,
    );

    expect($payload1->fingerprint())->not->toBe($payload2->fingerprint());
});
