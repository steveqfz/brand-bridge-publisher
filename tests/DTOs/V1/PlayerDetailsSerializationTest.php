<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\PlayerDetailsDTO;
use BrandBridge\Enums\PlayerStatus;

it('round-trips through toArray and fromArray with all fields populated', function (): void {
    $details = new PlayerDetailsDTO(
        gender: 'male',
        address: '123 Main Street',
        city: 'Valletta',
        postalCode: 'VLT 1234',
        region: 'South Eastern',
        status: PlayerStatus::Active,
        totalDeposits: 15000.50,
        totalWithdrawals: 5000.25,
        netRevenue: 10000.25,
        lastDepositAt: new DateTimeImmutable('2024-03-15T09:00:00+00:00'),
        vipSince: new DateTimeImmutable('2023-06-01T00:00:00+00:00'),
    );

    $array = $details->toArray();
    $restored = PlayerDetailsDTO::fromArray($array);

    expect($restored->gender)->toBe('male')
        ->and($restored->address)->toBe('123 Main Street')
        ->and($restored->city)->toBe('Valletta')
        ->and($restored->postalCode)->toBe('VLT 1234')
        ->and($restored->region)->toBe('South Eastern')
        ->and($restored->status)->toBe(PlayerStatus::Active)
        ->and($restored->totalDeposits)->toBe(15000.50)
        ->and($restored->totalWithdrawals)->toBe(5000.25)
        ->and($restored->netRevenue)->toBe(10000.25)
        ->and($restored->lastDepositAt->format(DATE_RFC3339))->toBe('2024-03-15T09:00:00+00:00')
        ->and($restored->vipSince->format(DATE_RFC3339))->toBe('2023-06-01T00:00:00+00:00');
});

it('round-trips with nullable fields set to null', function (): void {
    $details = new PlayerDetailsDTO(
        gender: null,
        address: null,
        city: null,
        postalCode: null,
        region: null,
        status: PlayerStatus::Inactive,
        totalDeposits: 0.0,
        totalWithdrawals: 0.0,
        netRevenue: 0.0,
        lastDepositAt: null,
        vipSince: null,
    );

    $array = $details->toArray();
    $restored = PlayerDetailsDTO::fromArray($array);

    expect($restored->gender)->toBeNull()
        ->and($restored->address)->toBeNull()
        ->and($restored->city)->toBeNull()
        ->and($restored->postalCode)->toBeNull()
        ->and($restored->region)->toBeNull()
        ->and($restored->lastDepositAt)->toBeNull()
        ->and($restored->vipSince)->toBeNull();
});
