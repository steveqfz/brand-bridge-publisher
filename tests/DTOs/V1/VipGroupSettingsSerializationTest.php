<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\Vip\VipGroupSettingsDTO;

it('round-trips through toArray and fromArray with all fields populated', function (): void {
    $settings = new VipGroupSettingsDTO(
        maxMembers: 250,
        autoAssign: true,
        criteria: 'lifetime_points > 10000',
    );

    $array = $settings->toArray();
    $restored = VipGroupSettingsDTO::fromArray($array);

    expect($restored->maxMembers)->toBe(250)
        ->and($restored->autoAssign)->toBeTrue()
        ->and($restored->criteria)->toBe('lifetime_points > 10000');
});

it('round-trips with criteria set to null', function (): void {
    $settings = new VipGroupSettingsDTO(
        maxMembers: 50,
        autoAssign: false,
        criteria: null,
    );

    $array = $settings->toArray();
    $restored = VipGroupSettingsDTO::fromArray($array);

    expect($restored->criteria)->toBeNull()
        ->and($restored->autoAssign)->toBeFalse();
});
