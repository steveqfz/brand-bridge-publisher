<?php

declare(strict_types=1);

namespace BrandBridge\Exceptions;

final class PlayerNotFoundException extends BrandBridgeException
{
    public function __construct(string $sourcePlayerId)
    {
        parent::__construct(sprintf('Player with source ID "%s" not found.', $sourcePlayerId));
    }
}
