<?php

declare(strict_types=1);

namespace BrandBridge\Contracts\Mappers;

use BrandBridge\DTOs\V1\Vip\VipCommentDTO;

interface VipCommentMapperInterface
{
    /** @return VipCommentDTO[] */
    public function mapAll(string $sourcePlayerId): array;
}
