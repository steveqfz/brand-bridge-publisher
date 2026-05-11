<?php

declare(strict_types=1);

namespace BrandBridge\Contracts\Mappers;

use BrandBridge\DTOs\V1\Vip\VipGroupDTO;

interface VipGroupMapperInterface
{
    public function map(string $sourcePlayerId): ?VipGroupDTO;
}
