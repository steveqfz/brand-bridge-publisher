<?php

declare(strict_types=1);

namespace BrandBridge\Contracts\Mappers;

use BrandBridge\DTOs\V1\Vip\VipReminderDTO;

interface VipReminderMapperInterface
{
    /** @return VipReminderDTO[] */
    public function mapAll(string $sourcePlayerId): array;
}
