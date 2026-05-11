<?php

declare(strict_types=1);

namespace BrandBridge\Contracts\Mappers;

use BrandBridge\DTOs\V1\Vip\VipProfileDTO;

interface VipProfileMapperInterface
{
    public function map(string $sourcePlayerId): ?VipProfileDTO;
}
