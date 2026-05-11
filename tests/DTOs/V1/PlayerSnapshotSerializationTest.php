<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\PlayerSnapshot;
use BrandBridge\Enums\BrandKey;
use BrandBridge\Enums\PlayerStatus;

it('round-trips through toArray and fromArray with all fields populated', function (): void {
    $snapshot = new PlayerSnapshot(
        sourcePlayerId: 'player-123',
        sourceBrand: BrandKey::Vegastars,
        vipSettingId: 10,
        username: 'johndoe',
        email: 'john@example.com',
        password: 'hashed-secret',
        currencyCode: 'EUR',
        status: PlayerStatus::ACTIVE,
        banned: false,
        excluded: false,
        suspended: false,
        kickedAt: null,
        lastLoggedInAt: new DateTimeImmutable('2024-03-20T14:30:00+00:00'),
        phoneVerifiedAt: new DateTimeImmutable('2024-03-01T09:00:00+00:00'),
        betLimit: 500.5,
        isTestPlayer: false,
        agentCode: 'AG-1',
        batchId: 'BAT-9',
        phoneNumber: '+35699123456',
        isHoldWithdrawal: false,
        uuid: 'a1b2c3d4-e5f6-7890-abcd-ef1234567890',
        lastOnlineAt: new DateTimeImmutable('2024-03-20T15:00:00+00:00'),
        lastOfflineAt: new DateTimeImmutable('2024-03-20T12:00:00+00:00'),
        referredById: 99,
        referredByCode: 'REF99',
        referredOrderNumber: 'ORD-1',
        preferredCurrency: 'USD',
        passwordInitiated: true,
        vipRewardSettingId: 2,
        globalId: 'global-player-123',
    );

    $array = $snapshot->toArray();
    $restored = PlayerSnapshot::fromArray($array);

    expect($restored->sourcePlayerId)->toBe('player-123')
        ->and($restored->sourceBrand)->toBe(BrandKey::Vegastars)
        ->and($restored->vipSettingId)->toBe(10)
        ->and($restored->username)->toBe('johndoe')
        ->and($restored->email)->toBe('john@example.com')
        ->and($restored->password)->toBe('hashed-secret')
        ->and($restored->currencyCode)->toBe('EUR')
        ->and($restored->status)->toBe(PlayerStatus::ACTIVE)
        ->and($restored->banned)->toBeFalse()
        ->and($restored->excluded)->toBeFalse()
        ->and($restored->suspended)->toBeFalse()
        ->and($restored->kickedAt)->toBeNull()
        ->and($restored->lastLoggedInAt->format(DATE_RFC3339))->toBe('2024-03-20T14:30:00+00:00')
        ->and($restored->phoneVerifiedAt->format(DATE_RFC3339))->toBe('2024-03-01T09:00:00+00:00')
        ->and($restored->betLimit)->toBe(500.5)
        ->and($restored->isTestPlayer)->toBeFalse()
        ->and($restored->agentCode)->toBe('AG-1')
        ->and($restored->batchId)->toBe('BAT-9')
        ->and($restored->phoneNumber)->toBe('+35699123456')
        ->and($restored->isHoldWithdrawal)->toBeFalse()
        ->and($restored->uuid)->toBe('a1b2c3d4-e5f6-7890-abcd-ef1234567890')
        ->and($restored->lastOnlineAt->format(DATE_RFC3339))->toBe('2024-03-20T15:00:00+00:00')
        ->and($restored->lastOfflineAt->format(DATE_RFC3339))->toBe('2024-03-20T12:00:00+00:00')
        ->and($restored->referredById)->toBe(99)
        ->and($restored->referredByCode)->toBe('REF99')
        ->and($restored->referredOrderNumber)->toBe('ORD-1')
        ->and($restored->preferredCurrency)->toBe('USD')
        ->and($restored->passwordInitiated)->toBeTrue()
        ->and($restored->vipRewardSettingId)->toBe(2)
        ->and($restored->globalId)->toBe('global-player-123');
});

it('round-trips with nullable fields set to null', function (): void {
    $snapshot = new PlayerSnapshot(
        sourcePlayerId: 'player-456',
        sourceBrand: BrandKey::Voltrush,
        vipSettingId: null,
        username: 'janedoe',
        email: 'jane@example.com',
        password: null,
        currencyCode: 'GBP',
        status: PlayerStatus::INACTIVE,
        banned: false,
        excluded: false,
        suspended: false,
        kickedAt: null,
        lastLoggedInAt: null,
        phoneVerifiedAt: null,
        betLimit: null,
        isTestPlayer: true,
        agentCode: null,
        batchId: null,
        phoneNumber: null,
        isHoldWithdrawal: false,
        uuid: 'b2c3d4e5-f6a7-8901-bcde-f12345678901',
        lastOnlineAt: null,
        lastOfflineAt: null,
        referredById: null,
        referredByCode: null,
        referredOrderNumber: null,
        preferredCurrency: null,
        passwordInitiated: false,
        vipRewardSettingId: null,
        globalId: null,
    );

    $array = $snapshot->toArray();
    $restored = PlayerSnapshot::fromArray($array);

    expect($restored->vipSettingId)->toBeNull()
        ->and($restored->password)->toBeNull()
        ->and($restored->kickedAt)->toBeNull()
        ->and($restored->lastLoggedInAt)->toBeNull()
        ->and($restored->phoneVerifiedAt)->toBeNull()
        ->and($restored->betLimit)->toBeNull()
        ->and($restored->agentCode)->toBeNull()
        ->and($restored->batchId)->toBeNull()
        ->and($restored->phoneNumber)->toBeNull()
        ->and($restored->lastOnlineAt)->toBeNull()
        ->and($restored->lastOfflineAt)->toBeNull()
        ->and($restored->referredById)->toBeNull()
        ->and($restored->referredByCode)->toBeNull()
        ->and($restored->referredOrderNumber)->toBeNull()
        ->and($restored->preferredCurrency)->toBeNull()
        ->and($restored->vipRewardSettingId)->toBeNull()
        ->and($restored->globalId)->toBeNull()
        ->and($restored->isTestPlayer)->toBeTrue();
});
