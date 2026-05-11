<?php

declare(strict_types=1);

namespace BrandBridge\Contracts\Mappers;

use BrandBridge\DTOs\V1\Vip\VipMilestoneDTO;

interface VipMilestoneMapperInterface
{
    /** @return VipMilestoneDTO[] */
    public function mapAll(string $sourcePlayerId): array;
}
