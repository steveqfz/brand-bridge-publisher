<?php

declare(strict_types=1);

namespace BrandBridge\Enums;

enum VipTier: string
{
    case Bronze = 'bronze';
    case Silver = 'silver';
    case Gold = 'gold';
    case Platinum = 'platinum';
    case Diamond = 'diamond';
    case Elite = 'elite';
}
