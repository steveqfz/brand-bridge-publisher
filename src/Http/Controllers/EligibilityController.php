<?php

declare(strict_types=1);

namespace BrandBridge\Http\Controllers;

use BrandBridge\Contracts\EligibilityCheckerInterface;
use Illuminate\Http\JsonResponse;

final class EligibilityController
{
    public function __construct(
        private readonly EligibilityCheckerInterface $eligibilityChecker,
    ) {}

    public function show(string $sourcePlayerId): JsonResponse
    {
        $result = $this->eligibilityChecker->check($sourcePlayerId);

        return new JsonResponse($result->toArray());
    }
}
