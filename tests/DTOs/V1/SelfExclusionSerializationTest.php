<?php

declare(strict_types=1);

use BrandBridge\DTOs\V1\ResponsibleGaming\SelfExclusionDTO;

it('round-trips through toArray and fromArray with all fields populated', function (): void {
    $exclusion = new SelfExclusionDTO(
        isActive: true,
        startDate: new DateTimeImmutable('2024-01-01T00:00:00+00:00'),
        endDate: new DateTimeImmutable('2024-07-01T00:00:00+00:00'),
        reason: 'Personal choice',
    );

    $array = $exclusion->toArray();
    $restored = SelfExclusionDTO::fromArray($array);

    expect($restored->isActive)->toBeTrue()
        ->and($restored->startDate->format(DATE_RFC3339))->toBe('2024-01-01T00:00:00+00:00')
        ->and($restored->endDate->format(DATE_RFC3339))->toBe('2024-07-01T00:00:00+00:00')
        ->and($restored->reason)->toBe('Personal choice');
});

it('round-trips with nullable fields set to null (permanent exclusion)', function (): void {
    $exclusion = new SelfExclusionDTO(
        isActive: true,
        startDate: new DateTimeImmutable('2024-01-01T00:00:00+00:00'),
        endDate: null,
        reason: null,
    );

    $array = $exclusion->toArray();
    $restored = SelfExclusionDTO::fromArray($array);

    expect($restored->endDate)->toBeNull()
        ->and($restored->reason)->toBeNull();
});
