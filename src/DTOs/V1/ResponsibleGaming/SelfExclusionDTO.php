<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1\ResponsibleGaming;

final readonly class SelfExclusionDTO
{
    public function __construct(
        /** Whether the self-exclusion is currently active */
        public bool $isActive,
        /** Start date of the self-exclusion period */
        public \DateTimeImmutable $startDate,
        /** End date of the self-exclusion period, null if permanent */
        public ?\DateTimeImmutable $endDate,
        /** Reason provided for self-exclusion */
        public ?string $reason,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            isActive: (bool) $data['is_active'],
            startDate: new \DateTimeImmutable($data['start_date']),
            endDate: isset($data['end_date']) ? new \DateTimeImmutable($data['end_date']) : null,
            reason: $data['reason'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'is_active' => $this->isActive,
            'start_date' => $this->startDate->format(\DATE_RFC3339),
            'end_date' => $this->endDate?->format(\DATE_RFC3339),
            'reason' => $this->reason,
        ];
    }
}
