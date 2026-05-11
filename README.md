# Brand Bridge Publisher

DTOs and Laravel publisher infrastructure for cross-brand player onboarding.

This package provides a zero-boilerplate solution for source brands to expose VIP player data to the Blackroom brand via HTTP. Source brands `composer require` this package, publish stubs, fill in mappers — done. No routes, controllers, or middleware to write.

The same package is installable by Blackroom (consumer) to access the canonical DTOs for deserialization. The publisher infrastructure (routes, controllers) is loaded conditionally via config — off by default.

## Installation

```bash
composer require your-org/brand-bridge-publisher
```

## For Source Brands (3-Step Setup)

### Step 1: Publish config

```bash
php artisan vendor:publish --tag=brand-bridge-config
```

Then set your `.env`:

```env
BRAND_BRIDGE_BRAND_KEY=vegastars
BRAND_BRIDGE_PUBLISHER_ENABLED=true
BRAND_BRIDGE_SIGNING_KEY=your-shared-secret-with-blackroom
BRAND_BRIDGE_DB_CONNECTION=mysql
```

### Step 2: Publish mapper stubs

```bash
php artisan vendor:publish --tag=brand-bridge-mappers
```

This creates 13 mapper files in `app/BrandBridge/Mappers/`.

### Step 3: Implement mappers

Open each mapper in `app/BrandBridge/Mappers/` and fill in the `map()` / `mapAll()` methods with your brand's database queries. Each file has TODO comments showing where to customize.

## For Consumers (Blackroom)

Just use the DTOs from `BrandBridge\DTOs\V1\*` — no setup needed. The publisher infrastructure won't load since `BRAND_BRIDGE_PUBLISHER_ENABLED` defaults to `false`.

```php
use BrandBridge\DTOs\V1\OnboardingPayload;

$payload = OnboardingPayload::fromArray($responseData['data']);
$payload->player->email; // Access player data
$payload->vipProfile?->tier; // Access VIP data
```

## Configuration Reference

| Key | Env Variable | Default | Description |
|-----|-------------|---------|-------------|
| `brand_key` | `BRAND_BRIDGE_BRAND_KEY` | `null` | Your brand identifier (`vegastars`, `voltrush`, `smokos`, `razed`) |
| `connection` | `BRAND_BRIDGE_DB_CONNECTION` | `mysql` | Database connection name for mapper queries |
| `publisher.enabled` | `BRAND_BRIDGE_PUBLISHER_ENABLED` | `false` | Enable API endpoints (source brands only) |
| `publisher.signing_key` | `BRAND_BRIDGE_SIGNING_KEY` | `null` | HMAC shared secret with Blackroom |
| `publisher.timestamp_tolerance_seconds` | — | `300` | Anti-replay window in seconds |
| `publisher.rate_limit_per_minute` | — | `60` | Rate limit per IP per minute |

## API Endpoints

All endpoints are prefixed with `/api/cross-brand/bridge`.

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| GET | `/health` | None | Health check |
| GET | `/eligibility/{sourcePlayerId}` | HMAC | Check player eligibility |
| GET | `/players/{sourcePlayerId}/onboarding-payload` | HMAC | Full player data snapshot |

## Mapper Implementation Guide

Each mapper has one method that does both fetching and mapping. Example:

```php
<?php

declare(strict_types=1);

namespace App\BrandBridge\Mappers;

use BrandBridge\Contracts\Mappers\PlayerSnapshotMapperInterface;
use BrandBridge\DTOs\V1\PlayerSnapshot;
use BrandBridge\Enums\BrandKey;
use Illuminate\Database\DatabaseManager;

final class PlayerSnapshotMapper implements PlayerSnapshotMapperInterface
{
    public function __construct(
        private readonly DatabaseManager $db,
    ) {}

    public function map(string $sourcePlayerId): ?PlayerSnapshot
    {
        $row = $this->db->connection(config('brand-bridge.connection'))
            ->table('players')
            ->where('player_id', $sourcePlayerId)
            ->first();

        if (!$row) {
            return null;
        }

        return new PlayerSnapshot(
            sourcePlayerId: (string) $row->player_id,
            sourceBrand: BrandKey::from(config('brand-bridge.brand_key')),
            email: $row->email,
            username: $row->username,
            firstName: $row->first_name ?? null,
            lastName: $row->last_name ?? null,
            dateOfBirth: $row->dob ? new \DateTimeImmutable($row->dob) : null,
            country: strtoupper($row->country),
            phoneNumber: $row->phone ?? null,
            registeredAt: new \DateTimeImmutable($row->created_at),
            lastLoginAt: $row->last_login ? new \DateTimeImmutable($row->last_login) : null,
            kycReference: $row->kyc_ref ?? null,
            currency: strtoupper($row->currency ?? 'USD'),
            language: $row->language ?? null,
        );
    }
}
```

## Versioning Policy

This package follows [Semantic Versioning](https://semver.org/) strictly:

- **MAJOR**: Breaking changes to DTO shapes, removed endpoints, or changed HMAC algorithm
- **MINOR**: New DTOs, new optional fields, new endpoints
- **PATCH**: Bug fixes, documentation updates

## Override Points

### Custom Eligibility Checker

Bind your own implementation of `EligibilityCheckerInterface` in your app's service provider:

```php
$this->app->bind(
    \BrandBridge\Contracts\EligibilityCheckerInterface::class,
    \App\BrandBridge\CustomEligibilityChecker::class,
);
```

### Custom Assembler

If you need to customize the payload assembly logic, extend or replace `OnboardingPayloadAssembler` by rebinding it in the container.

## Troubleshooting

| Error | Cause | Fix |
|-------|-------|-----|
| `MapperNotPublishedException` | Mapper class not found at expected path | Run `php artisan vendor:publish --tag=brand-bridge-mappers` |
| `500: Signing key not configured` | Missing HMAC key | Set `BRAND_BRIDGE_SIGNING_KEY` in `.env` |
| `401: Missing required Brand Bridge headers` | Request missing HMAC headers | Include `X-Brand-Bridge-Signature`, `X-Brand-Bridge-Timestamp`, `X-Brand-Bridge-Version` |
| `401: Request timestamp outside tolerance` | Clock skew or replay attack | Sync server clocks; ensure timestamp is within 5 min |
| `401: Invalid signature` | Wrong signing key or tampered request | Verify both sides use the same `BRAND_BRIDGE_SIGNING_KEY` |
| `404: player_not_found` | PlayerSnapshotMapper returned null | Check your mapper query and player ID format |
