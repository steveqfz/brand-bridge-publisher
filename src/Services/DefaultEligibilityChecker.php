<?php

declare(strict_types=1);

namespace BrandBridge\Services;

use BrandBridge\Contracts\EligibilityCheckerInterface;
use BrandBridge\DTOs\V1\EligibilityResult;

final class DefaultEligibilityChecker implements EligibilityCheckerInterface
{
    public function check(string $sourcePlayerId): EligibilityResult
    {
        return new EligibilityResult(
            eligible: true,
            reasons: [],
            checkedAt: new \DateTimeImmutable(),
        );
    }
}
