<?php

declare(strict_types=1);

namespace BrandBridge\Http;

final class ApiPaths
{
    public const PREFIX = 'api/cross-brand/bridge';
    public const HEALTH = '/health';
    public const ELIGIBILITY = '/eligibility/{sourcePlayerId}';
    public const ONBOARDING_PAYLOAD = '/players/{sourcePlayerId}/onboarding-payload';
}
