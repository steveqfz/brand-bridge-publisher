<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\Vip\VipMilestoneDTO;

it('round-trips through toArray and fromArray with all fields populated', function (): void {
    $milestone = new VipMilestoneDTO(
        milestoneId: 'ms-001',
        title: 'First 10k Deposit',
        description: 'Player reached 10,000 EUR in total deposits.',
        achievedAt: new DateTimeImmutable('2024-03-01T12:00:00+00:00'),
        targetValue: 10000.00,
        currentValue: 10500.00,
    );

    $array = $milestone->toArray();
    $restored = VipMilestoneDTO::fromArray($array);

    expect($restored->milestoneId)->toBe('ms-001')
        ->and($restored->title)->toBe('First 10k Deposit')
        ->and($restored->description)->toBe('Player reached 10,000 EUR in total deposits.')
        ->and($restored->achievedAt->format(DATE_RFC3339))->toBe('2024-03-01T12:00:00+00:00')
        ->and($restored->targetValue)->toBe(10000.00)
        ->and($restored->currentValue)->toBe(10500.00);
});

it('round-trips with nullable fields set to null', function (): void {
    $milestone = new VipMilestoneDTO(
        milestoneId: 'ms-002',
        title: '50k Lifetime Target',
        description: null,
        achievedAt: null,
        targetValue: null,
        currentValue: null,
    );

    $array = $milestone->toArray();
    $restored = VipMilestoneDTO::fromArray($array);

    expect($restored->description)->toBeNull()
        ->and($restored->achievedAt)->toBeNull()
        ->and($restored->targetValue)->toBeNull()
        ->and($restored->currentValue)->toBeNull();
});
