<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\Vip\VipProfileDTO;
use BrandBridge\Enums\VipTier;

it('round-trips through toArray and fromArray with all fields populated', function (): void {
    $profile = new VipProfileDTO(
        tier: VipTier::Gold,
        points: 5000,
        lifetimePoints: 25000,
        tierAchievedAt: new DateTimeImmutable('2023-06-01T00:00:00+00:00'),
        tierExpiresAt: new DateTimeImmutable('2024-06-01T00:00:00+00:00'),
        isRetained: false,
    );

    $array = $profile->toArray();
    $restored = VipProfileDTO::fromArray($array);

    expect($restored->tier)->toBe(VipTier::Gold)
        ->and($restored->points)->toBe(5000)
        ->and($restored->lifetimePoints)->toBe(25000)
        ->and($restored->tierAchievedAt->format(DATE_RFC3339))->toBe('2023-06-01T00:00:00+00:00')
        ->and($restored->tierExpiresAt->format(DATE_RFC3339))->toBe('2024-06-01T00:00:00+00:00')
        ->and($restored->isRetained)->toBeFalse();
});

it('round-trips with nullable fields set to null', function (): void {
    $profile = new VipProfileDTO(
        tier: VipTier::Bronze,
        points: 100,
        lifetimePoints: 100,
        tierAchievedAt: null,
        tierExpiresAt: null,
        isRetained: true,
    );

    $array = $profile->toArray();
    $restored = VipProfileDTO::fromArray($array);

    expect($restored->tierAchievedAt)->toBeNull()
        ->and($restored->tierExpiresAt)->toBeNull()
        ->and($restored->isRetained)->toBeTrue();
});
