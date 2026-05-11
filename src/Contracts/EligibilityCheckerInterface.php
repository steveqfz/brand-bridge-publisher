<?php

declare(strict_types=1);

namespace BrandBridge\Contracts;

use BrandBridge\DTOs\V1\EligibilityResult;

interface EligibilityCheckerInterface
{
    public function check(string $sourcePlayerId): EligibilityResult;
}
