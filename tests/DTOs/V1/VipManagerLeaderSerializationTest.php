<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\Vip\VipManagerLeaderDTO;

it('round-trips through toArray and fromArray', function (): void {
    $leader = new VipManagerLeaderDTO(
        leaderId: 'lead-001',
        name: 'Bob Johnson',
        email: 'bob@company.com',
    );

    $array = $leader->toArray();
    $restored = VipManagerLeaderDTO::fromArray($array);

    expect($restored->leaderId)->toBe('lead-001')
        ->and($restored->name)->toBe('Bob Johnson')
        ->and($restored->email)->toBe('bob@company.com');
});
