<?php

declare(strict_types=1);

namespace BrandBridge\Enums;

enum PlayerStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
    case SelfExcluded = 'self_excluded';
    case Closed = 'closed';
}
