<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\ResponsibleGaming\BettingLimitDTO;

it('round-trips through toArray and fromArray with all fields populated', function (): void {
    $limit = new BettingLimitDTO(
        limitType: 'daily',
        amount: 500.00,
        currency: 'EUR',
        effectiveFrom: new DateTimeImmutable('2024-01-01T00:00:00+00:00'),
        effectiveUntil: new DateTimeImmutable('2024-12-31T23:59:59+00:00'),
    );

    $array = $limit->toArray();
    $restored = BettingLimitDTO::fromArray($array);

    expect($restored->limitType)->toBe('daily')
        ->and($restored->amount)->toBe(500.00)
        ->and($restored->currency)->toBe('EUR')
        ->and($restored->effectiveFrom->format(DATE_RFC3339))->toBe('2024-01-01T00:00:00+00:00')
        ->and($restored->effectiveUntil->format(DATE_RFC3339))->toBe('2024-12-31T23:59:59+00:00');
});

it('round-trips with effectiveUntil set to null (indefinite)', function (): void {
    $limit = new BettingLimitDTO(
        limitType: 'monthly',
        amount: 10000.00,
        currency: 'USD',
        effectiveFrom: new DateTimeImmutable('2024-06-01T00:00:00+00:00'),
        effectiveUntil: null,
    );

    $array = $limit->toArray();
    $restored = BettingLimitDTO::fromArray($array);

    expect($restored->effectiveUntil)->toBeNull()
        ->and($restored->limitType)->toBe('monthly');
});
