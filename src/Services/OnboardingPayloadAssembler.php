<?php

declare(strict_types=1);

namespace BrandBridge\Services;

use BrandBridge\Contracts\Mappers\BettingLimitMapperInterface;
use BrandBridge\Contracts\Mappers\PlayerDetailsMapperInterface;
use BrandBridge\Contracts\Mappers\PlayerSnapshotMapperInterface;
use BrandBridge\Contracts\Mappers\PlayerTagMapperInterface;
use BrandBridge\Contracts\Mappers\SelfExclusionMapperInterface;
use BrandBridge\Contracts\Mappers\VipCommentMapperInterface;
use BrandBridge\Contracts\Mappers\VipContactMapperInterface;
use BrandBridge\Contracts\Mappers\VipGroupMapperInterface;
use BrandBridge\Contracts\Mappers\VipManagerMapperInterface;
use BrandBridge\Contracts\Mappers\VipMilestoneMapperInterface;
use BrandBridge\Contracts\Mappers\VipProfileMapperInterface;
use BrandBridge\Contracts\Mappers\VipPromiseMapperInterface;
use BrandBridge\Contracts\Mappers\VipReminderMapperInterface;
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
        private readonly BettingLimitMapperInterface $bettingLimitMapper,
        private readonly SelfExclusionMapperInterface $selfExclusionMapper,
        private readonly VipProfileMapperInterface $vipProfileMapper,
        private readonly VipGroupMapperInterface $vipGroupMapper,
        private readonly VipManagerMapperInterface $vipManagerMapper,
        private readonly VipCommentMapperInterface $vipCommentMapper,
        private readonly VipMilestoneMapperInterface $vipMilestoneMapper,
        private readonly VipPromiseMapperInterface $vipPromiseMapper,
        private readonly VipReminderMapperInterface $vipReminderMapper,
        private readonly VipContactMapperInterface $vipContactMapper,
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
            bettingLimit: $this->bettingLimitMapper->map($sourcePlayerId),
            selfExclusion: $this->selfExclusionMapper->map($sourcePlayerId),
            vipProfile: $this->vipProfileMapper->map($sourcePlayerId),
            vipGroup: $this->vipGroupMapper->map($sourcePlayerId),
            vipManager: $this->vipManagerMapper->map($sourcePlayerId),
            vipComments: $this->vipCommentMapper->mapAll($sourcePlayerId),
            vipMilestones: $this->vipMilestoneMapper->mapAll($sourcePlayerId),
            vipPromises: $this->vipPromiseMapper->mapAll($sourcePlayerId),
            vipReminders: $this->vipReminderMapper->mapAll($sourcePlayerId),
            vipContacts: $this->vipContactMapper->mapAll($sourcePlayerId),
            snapshotTakenAt: new \DateTimeImmutable(),
        );
    }
}
