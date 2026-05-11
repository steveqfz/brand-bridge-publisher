<?php

declare(strict_types=1);

namespace BrandBridge\Contracts\Mappers;

use BrandBridge\DTOs\V1\OauthPlayersDTO;

interface OauthPlayersMapperInterface
{
    /** @return OauthPlayersDTO[] */
    public function mapAll(string $sourcePlayerId): array;
}
