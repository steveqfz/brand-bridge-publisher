<?php

declare(strict_types=1);

namespace BrandBridge\Contracts\Mappers;

use BrandBridge\DTOs\V1\Vip\VipManagerDTO;

interface VipManagerMapperInterface
{
    public function map(string $sourcePlayerId): ?VipManagerDTO;
}
