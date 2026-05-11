<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\EligibilityResult;

it('round-trips through toArray and fromArray when eligible', function (): void {
    $result = new EligibilityResult(
        eligible: true,
        reasons: [],
        checkedAt: new DateTimeImmutable('2024-03-20T10:00:00+00:00'),
    );

    $array = $result->toArray();
    $restored = EligibilityResult::fromArray($array);

    expect($restored->eligible)->toBeTrue()
        ->and($restored->reasons)->toBe([])
        ->and($restored->checkedAt->format(DATE_RFC3339))->toBe('2024-03-20T10:00:00+00:00');
});

it('round-trips through toArray and fromArray when ineligible with reasons', function (): void {
    $result = new EligibilityResult(
        eligible: false,
        reasons: ['Player is self-excluded', 'Account is suspended'],
        checkedAt: new DateTimeImmutable('2024-03-20T10:00:00+00:00'),
    );

    $array = $result->toArray();
    $restored = EligibilityResult::fromArray($array);

    expect($restored->eligible)->toBeFalse()
        ->and($restored->reasons)->toBe(['Player is self-excluded', 'Account is suspended'])
        ->and($restored->checkedAt->format(DATE_RFC3339))->toBe('2024-03-20T10:00:00+00:00');
});
