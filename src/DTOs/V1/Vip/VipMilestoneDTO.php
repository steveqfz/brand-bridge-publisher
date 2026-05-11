<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1\Vip;

final readonly class VipMilestoneDTO
{
    public function __construct(
        /** Unique identifier for the milestone */
        public string $milestoneId,
        /** Milestone title */
        public string $title,
        /** Optional description of the milestone */
        public ?string $description,
        /** Date the milestone was achieved, null if not yet achieved */
        public ?\DateTimeImmutable $achievedAt,
        /** Target value to reach for this milestone */
        public ?float $targetValue,
        /** Current progress value toward the milestone */
        public ?float $currentValue,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            milestoneId: (string) $data['milestone_id'],
            title: (string) $data['title'],
            description: $data['description'] ?? null,
            achievedAt: isset($data['achieved_at']) ? new \DateTimeImmutable($data['achieved_at']) : null,
            targetValue: isset($data['target_value']) ? (float) $data['target_value'] : null,
            currentValue: isset($data['current_value']) ? (float) $data['current_value'] : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'milestone_id' => $this->milestoneId,
            'title' => $this->title,
            'description' => $this->description,
            'achieved_at' => $this->achievedAt?->format(\DATE_RFC3339),
            'target_value' => $this->targetValue,
            'current_value' => $this->currentValue,
        ];
    }
}
