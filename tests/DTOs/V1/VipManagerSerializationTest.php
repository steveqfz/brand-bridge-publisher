<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\Vip\VipManagerDTO;
use BrandBridge\DTOs\V1\Vip\VipManagerLeaderDTO;

it('round-trips through toArray and fromArray with all fields populated', function (): void {
    $manager = new VipManagerDTO(
        managerId: 'mgr-001',
        name: 'Alice Smith',
        email: 'alice@company.com',
        leader: new VipManagerLeaderDTO(
            leaderId: 'lead-001',
            name: 'Bob Johnson',
            email: 'bob@company.com',
        ),
        assignedAt: new DateTimeImmutable('2023-09-01T08:00:00+00:00'),
    );

    $array = $manager->toArray();
    $restored = VipManagerDTO::fromArray($array);

    expect($restored->managerId)->toBe('mgr-001')
        ->and($restored->name)->toBe('Alice Smith')
        ->and($restored->email)->toBe('alice@company.com')
        ->and($restored->leader)->not->toBeNull()
        ->and($restored->leader->leaderId)->toBe('lead-001')
        ->and($restored->leader->name)->toBe('Bob Johnson')
        ->and($restored->leader->email)->toBe('bob@company.com')
        ->and($restored->assignedAt->format(DATE_RFC3339))->toBe('2023-09-01T08:00:00+00:00');
});

it('round-trips with leader set to null', function (): void {
    $manager = new VipManagerDTO(
        managerId: 'mgr-002',
        name: 'Charlie Brown',
        email: 'charlie@company.com',
        leader: null,
        assignedAt: new DateTimeImmutable('2024-01-01T00:00:00+00:00'),
    );

    $array = $manager->toArray();
    $restored = VipManagerDTO::fromArray($array);

    expect($restored->leader)->toBeNull();
});
