<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\PlayerTagDTO;

it('round-trips through toArray and fromArray', function (): void {
    $tag = new PlayerTagDTO(
        name: 'vip_level',
        value: 'gold',
        assignedAt: new DateTimeImmutable('2024-01-15T12:00:00+00:00'),
    );

    $array = $tag->toArray();
    $restored = PlayerTagDTO::fromArray($array);

    expect($restored->name)->toBe('vip_level')
        ->and($restored->value)->toBe('gold')
        ->and($restored->assignedAt->format(DATE_RFC3339))->toBe('2024-01-15T12:00:00+00:00');
});
