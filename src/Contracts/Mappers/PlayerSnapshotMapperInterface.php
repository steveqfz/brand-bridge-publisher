<?php

declare(strict_types=1);

namespace BrandBridge\Contracts\Mappers;

use BrandBridge\DTOs\V1\PlayerSnapshot;

interface PlayerSnapshotMapperInterface
{
    public function map(string $sourcePlayerId): ?PlayerSnapshot;
}
