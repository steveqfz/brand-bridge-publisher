<?php

declare(strict_types=1);

namespace BrandBridge\Http\Controllers;

use BrandBridge\Exceptions\PlayerNotFoundException;
use BrandBridge\Services\OnboardingPayloadAssembler;
use Illuminate\Http\JsonResponse;

final class PlayerSnapshotController
{
    public function __construct(
        private readonly OnboardingPayloadAssembler $assembler,
    ) {}

    public function show(string $sourcePlayerId): JsonResponse
    {
        try {
            $payload = $this->assembler->assemble($sourcePlayerId);
        } catch (PlayerNotFoundException) {
            return new JsonResponse([
                'error' => 'player_not_found',
            ], 404);
        }

        return new JsonResponse([
            'version' => 'v1',
            'data' => $payload->toArray(),
            'snapshot_taken_at' => $payload->snapshotTakenAt->format(\DATE_RFC3339),
        ]);
    }
}
