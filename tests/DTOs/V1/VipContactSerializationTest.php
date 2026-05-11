<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\Vip\VipContactDTO;

it('round-trips through toArray and fromArray', function (): void {
    $contact = new VipContactDTO(
        contactId: 'cnt-001',
        type: 'phone',
        value: '+35699888777',
        isPrimary: true,
        createdAt: new DateTimeImmutable('2023-12-01T10:00:00+00:00'),
    );

    $array = $contact->toArray();
    $restored = VipContactDTO::fromArray($array);

    expect($restored->contactId)->toBe('cnt-001')
        ->and($restored->type)->toBe('phone')
        ->and($restored->value)->toBe('+35699888777')
        ->and($restored->isPrimary)->toBeTrue()
        ->and($restored->createdAt->format(DATE_RFC3339))->toBe('2023-12-01T10:00:00+00:00');
});

it('handles non-primary contacts', function (): void {
    $contact = new VipContactDTO(
        contactId: 'cnt-002',
        type: 'email',
        value: 'vip@player.com',
        isPrimary: false,
        createdAt: new DateTimeImmutable('2024-01-15T14:00:00+00:00'),
    );

    $array = $contact->toArray();
    $restored = VipContactDTO::fromArray($array);

    expect($restored->isPrimary)->toBeFalse()
        ->and($restored->type)->toBe('email');
});
