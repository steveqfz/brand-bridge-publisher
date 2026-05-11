<?php

declare(strict_types=1);

namespace BrandBridge\Contracts\Mappers;

use BrandBridge\DTOs\V1\Vip\VipContactDTO;

interface VipContactMapperInterface
{
    /** @return VipContactDTO[] */
    public function mapAll(string $sourcePlayerId): array;
}
