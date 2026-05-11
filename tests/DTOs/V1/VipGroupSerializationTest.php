<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\Vip\VipGroupDTO;
use BrandBridge\DTOs\V1\Vip\VipGroupSettingsDTO;

it('round-trips through toArray and fromArray with all fields populated', function (): void {
    $group = new VipGroupDTO(
        groupId: 'grp-001',
        name: 'High Rollers',
        description: 'Players with deposits over 50k',
        settings: new VipGroupSettingsDTO(
            maxMembers: 100,
            autoAssign: true,
            criteria: 'total_deposits > 50000',
        ),
    );

    $array = $group->toArray();
    $restored = VipGroupDTO::fromArray($array);

    expect($restored->groupId)->toBe('grp-001')
        ->and($restored->name)->toBe('High Rollers')
        ->and($restored->description)->toBe('Players with deposits over 50k')
        ->and($restored->settings)->not->toBeNull()
        ->and($restored->settings->maxMembers)->toBe(100)
        ->and($restored->settings->autoAssign)->toBeTrue()
        ->and($restored->settings->criteria)->toBe('total_deposits > 50000');
});

it('round-trips with nullable fields set to null', function (): void {
    $group = new VipGroupDTO(
        groupId: 'grp-002',
        name: 'Basic VIP',
        description: null,
        settings: null,
    );

    $array = $group->toArray();
    $restored = VipGroupDTO::fromArray($array);

    expect($restored->description)->toBeNull()
        ->and($restored->settings)->toBeNull();
});
