<?php

declare(strict_types=1);

namespace BrandBridge\Services;

use BrandBridge\Contracts\Mappers\PlayerDetailsMapperInterface;
use BrandBridge\Contracts\Mappers\PlayerSnapshotMapperInterface;
use BrandBridge\Contracts\Mappers\PlayerTagMapperInterface;
use BrandBridge\DTOs\V1\OnboardingPayload;
use BrandBridge\Enums\BrandKey;
use BrandBridge\Enums\PayloadVersion;
use BrandBridge\Exceptions\PlayerNotFoundException;

final class OnboardingPayloadAssembler
{
    public function __construct(
        private readonly PlayerSnapshotMapperInterface $playerMapper,
        private readonly PlayerDetailsMapperInterface $detailsMapper,
        private readonly PlayerTagMapperInterface $tagMapper,
    ) {}

    public function assemble(string $sourcePlayerId): OnboardingPayload
    {
        $player = $this->playerMapper->map($sourcePlayerId);
        if ($player === null) {
            throw new PlayerNotFoundException($sourcePlayerId);
        }

        $brandKey = BrandKey::from(config('brand-bridge.brand_key'));

        return new OnboardingPayload(
            version: PayloadVersion::V1,
            sourceBrand: $brandKey,
            sourcePlayerId: $sourcePlayerId,
            player: $player,
            details: $this->detailsMapper->map($sourcePlayerId),
            tags: $this->tagMapper->mapAll($sourcePlayerId),
            snapshotTakenAt: new \DateTimeImmutable(),
        );
    }
}
