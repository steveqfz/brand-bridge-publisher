<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1\Vip;

final readonly class VipGroupSettingsDTO
{
    public function __construct(
        /** Maximum number of members allowed in the group */
        public int $maxMembers,
        /** Whether players are automatically assigned to this group */
        public bool $autoAssign,
        /** Criteria expression for auto-assignment eligibility */
        public ?string $criteria,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            maxMembers: (int) $data['max_members'],
            autoAssign: (bool) $data['auto_assign'],
            criteria: $data['criteria'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'max_members' => $this->maxMembers,
            'auto_assign' => $this->autoAssign,
            'criteria' => $this->criteria,
        ];
    }
}
