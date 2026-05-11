<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\Vip\VipPromiseDTO;

it('round-trips through toArray and fromArray with all fields populated', function (): void {
    $promise = new VipPromiseDTO(
        promiseId: 'prm-001',
        title: 'Birthday Bonus',
        description: 'Free spins package for player birthday.',
        promisedAt: new DateTimeImmutable('2024-04-01T10:00:00+00:00'),
        fulfilledAt: new DateTimeImmutable('2024-04-15T08:00:00+00:00'),
        status: 'fulfilled',
    );

    $array = $promise->toArray();
    $restored = VipPromiseDTO::fromArray($array);

    expect($restored->promiseId)->toBe('prm-001')
        ->and($restored->title)->toBe('Birthday Bonus')
        ->and($restored->description)->toBe('Free spins package for player birthday.')
        ->and($restored->promisedAt->format(DATE_RFC3339))->toBe('2024-04-01T10:00:00+00:00')
        ->and($restored->fulfilledAt->format(DATE_RFC3339))->toBe('2024-04-15T08:00:00+00:00')
        ->and($restored->status)->toBe('fulfilled');
});

it('round-trips with nullable fields set to null', function (): void {
    $promise = new VipPromiseDTO(
        promiseId: 'prm-002',
        title: 'Cashback Offer',
        description: null,
        promisedAt: new DateTimeImmutable('2024-05-01T10:00:00+00:00'),
        fulfilledAt: null,
        status: 'pending',
    );

    $array = $promise->toArray();
    $restored = VipPromiseDTO::fromArray($array);

    expect($restored->description)->toBeNull()
        ->and($restored->fulfilledAt)->toBeNull()
        ->and($restored->status)->toBe('pending');
});
