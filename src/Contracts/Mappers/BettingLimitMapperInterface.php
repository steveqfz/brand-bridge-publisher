<?php

declare(strict_types=1);

namespace BrandBridge\Contracts\Mappers;

use BrandBridge\DTOs\V1\ResponsibleGaming\BettingLimitDTO;

interface BettingLimitMapperInterface
{
    public function map(string $sourcePlayerId): ?BettingLimitDTO;
}
