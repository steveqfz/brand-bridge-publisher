<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\Vip\VipReminderDTO;

it('round-trips through toArray and fromArray with all fields populated', function (): void {
    $reminder = new VipReminderDTO(
        reminderId: 'rem-001',
        title: 'Follow up on bonus offer',
        description: 'Call player about the cashback proposal.',
        dueAt: new DateTimeImmutable('2024-04-20T09:00:00+00:00'),
        completedAt: new DateTimeImmutable('2024-04-19T16:00:00+00:00'),
        isCompleted: true,
    );

    $array = $reminder->toArray();
    $restored = VipReminderDTO::fromArray($array);

    expect($restored->reminderId)->toBe('rem-001')
        ->and($restored->title)->toBe('Follow up on bonus offer')
        ->and($restored->description)->toBe('Call player about the cashback proposal.')
        ->and($restored->dueAt->format(DATE_RFC3339))->toBe('2024-04-20T09:00:00+00:00')
        ->and($restored->completedAt->format(DATE_RFC3339))->toBe('2024-04-19T16:00:00+00:00')
        ->and($restored->isCompleted)->toBeTrue();
});

it('round-trips with nullable fields set to null', function (): void {
    $reminder = new VipReminderDTO(
        reminderId: 'rem-002',
        title: 'Monthly check-in',
        description: null,
        dueAt: new DateTimeImmutable('2024-05-01T10:00:00+00:00'),
        completedAt: null,
        isCompleted: false,
    );

    $array = $reminder->toArray();
    $restored = VipReminderDTO::fromArray($array);

    expect($restored->description)->toBeNull()
        ->and($restored->completedAt)->toBeNull()
        ->and($restored->isCompleted)->toBeFalse();
});
