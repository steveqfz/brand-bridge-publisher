<?php

declare(strict_types=1);

namespace BrandBridge\Enums;

enum PlayerStatus: string
{
    case INACTIVE = 'inactive';

    case ACTIVE = 'active';

    case EXCLUDED = 'excluded';

    case KICKED = 'kicked';

    case BANNED = 'banned';

    case SUSPENDED = 'suspended';

    case TAKE_A_BREAK = 'take_a_break';

    case SELF_EXCLUSION = 'self_exclusion';

    case ACCOUNT_CLOSURE = 'account_closure';
}
