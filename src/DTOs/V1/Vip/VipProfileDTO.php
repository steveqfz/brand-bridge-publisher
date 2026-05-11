<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1\Vip;

use BrandBridge\Enums\VipTier;

final readonly class VipProfileDTO
{
    public function __construct(
        /** Current VIP tier of the player */
        public VipTier $tier,
        /** Current points balance */
        public int $points,
        /** Total lifetime points accumulated */
        public int $lifetimePoints,
        /** Date the current tier was achieved */
        public ?\DateTimeImmutable $tierAchievedAt,
        /** Date the current tier expires if not renewed */
        public ?\DateTimeImmutable $tierExpiresAt,
        /** Whether the player was retained at current tier */
        public bool $isRetained,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            tier: VipTier::from((string) $data['tier']),
            points: (int) $data['points'],
            lifetimePoints: (int) $data['lifetime_points'],
            tierAchievedAt: isset($data['tier_achieved_at']) ? new \DateTimeImmutable($data['tier_achieved_at']) : null,
            tierExpiresAt: isset($data['tier_expires_at']) ? new \DateTimeImmutable($data['tier_expires_at']) : null,
            isRetained: (bool) $data['is_retained'],
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'tier' => $this->tier->value,
            'points' => $this->points,
            'lifetime_points' => $this->lifetimePoints,
            'tier_achieved_at' => $this->tierAchievedAt?->format(\DATE_RFC3339),
            'tier_expires_at' => $this->tierExpiresAt?->format(\DATE_RFC3339),
            'is_retained' => $this->isRetained,
        ];
    }
}
