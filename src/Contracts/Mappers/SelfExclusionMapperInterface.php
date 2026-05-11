<?php

declare(strict_types=1);

namespace BrandBridge\Contracts\Mappers;

use BrandBridge\DTOs\V1\ResponsibleGaming\SelfExclusionDTO;

interface SelfExclusionMapperInterface
{
    public function map(string $sourcePlayerId): ?SelfExclusionDTO;
}
