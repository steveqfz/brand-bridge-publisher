<?php

declare(strict_types=1);

namespace BrandBridge\Exceptions;

final class InvalidSignatureException extends BrandBridgeException
{
    public function __construct(string $reason = 'Invalid signature')
    {
        parent::__construct($reason);
    }
}
