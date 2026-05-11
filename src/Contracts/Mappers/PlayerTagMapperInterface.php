<?php

declare(strict_types=1);

namespace BrandBridge\Contracts\Mappers;

use BrandBridge\DTOs\V1\PlayerTagDTO;

interface PlayerTagMapperInterface
{
    /** @return PlayerTagDTO[] */
    public function mapAll(string $sourcePlayerId): array;
}
