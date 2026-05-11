<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\PlayerSnapshot;
use BrandBridge\Enums\BrandKey;

it('round-trips through toArray and fromArray with all fields populated', function (): void {
    $snapshot = new PlayerSnapshot(
        sourcePlayerId: 'player-123',
        sourceBrand: BrandKey::Vegastars,
        email: 'john@example.com',
        username: 'johndoe',
        firstName: 'John',
        lastName: 'Doe',
        dateOfBirth: new DateTimeImmutable('1990-05-15'),
        country: 'MT',
        phoneNumber: '+35699123456',
        registeredAt: new DateTimeImmutable('2023-01-10T10:00:00+00:00'),
        lastLoginAt: new DateTimeImmutable('2024-03-20T14:30:00+00:00'),
        kycReference: 'KYC-REF-001',
        currency: 'EUR',
        language: 'en',
    );

    $array = $snapshot->toArray();
    $restored = PlayerSnapshot::fromArray($array);

    expect($restored->sourcePlayerId)->toBe('player-123')
        ->and($restored->sourceBrand)->toBe(BrandKey::Vegastars)
        ->and($restored->email)->toBe('john@example.com')
        ->and($restored->username)->toBe('johndoe')
        ->and($restored->firstName)->toBe('John')
        ->and($restored->lastName)->toBe('Doe')
        ->and($restored->dateOfBirth->format('Y-m-d'))->toBe('1990-05-15')
        ->and($restored->country)->toBe('MT')
        ->and($restored->phoneNumber)->toBe('+35699123456')
        ->and($restored->registeredAt->format(DATE_RFC3339))->toBe('2023-01-10T10:00:00+00:00')
        ->and($restored->lastLoginAt->format(DATE_RFC3339))->toBe('2024-03-20T14:30:00+00:00')
        ->and($restored->kycReference)->toBe('KYC-REF-001')
        ->and($restored->currency)->toBe('EUR')
        ->and($restored->language)->toBe('en');
});

it('round-trips with nullable fields set to null', function (): void {
    $snapshot = new PlayerSnapshot(
        sourcePlayerId: 'player-456',
        sourceBrand: BrandKey::Voltrush,
        email: 'jane@example.com',
        username: 'janedoe',
        firstName: null,
        lastName: null,
        dateOfBirth: null,
        country: 'GB',
        phoneNumber: null,
        registeredAt: new DateTimeImmutable('2024-06-01T08:00:00+00:00'),
        lastLoginAt: null,
        kycReference: null,
        currency: 'GBP',
        language: null,
    );

    $array = $snapshot->toArray();
    $restored = PlayerSnapshot::fromArray($array);

    expect($restored->firstName)->toBeNull()
        ->and($restored->lastName)->toBeNull()
        ->and($restored->dateOfBirth)->toBeNull()
        ->and($restored->phoneNumber)->toBeNull()
        ->and($restored->lastLoginAt)->toBeNull()
        ->and($restored->kycReference)->toBeNull()
        ->and($restored->language)->toBeNull();
});
