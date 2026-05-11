<?php

declare(strict_types=1);

namespace BrandBridge\Contracts\Mappers;

use BrandBridge\DTOs\V1\Vip\VipPromiseDTO;

interface VipPromiseMapperInterface
{
    /** @return VipPromiseDTO[] */
    public function mapAll(string $sourcePlayerId): array;
}
