<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1\Vip;

final readonly class VipReminderDTO
{
    public function __construct(
        /** Unique identifier for the reminder */
        public string $reminderId,
        /** Reminder title */
        public string $title,
        /** Optional detailed description of the reminder */
        public ?string $description,
        /** Date and time the reminder is due */
        public \DateTimeImmutable $dueAt,
        /** Date and time the reminder was completed, null if pending */
        public ?\DateTimeImmutable $completedAt,
        /** Whether the reminder has been completed */
        public bool $isCompleted,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            reminderId: (string) $data['reminder_id'],
            title: (string) $data['title'],
            description: $data['description'] ?? null,
            dueAt: new \DateTimeImmutable($data['due_at']),
            completedAt: isset($data['completed_at']) ? new \DateTimeImmutable($data['completed_at']) : null,
            isCompleted: (bool) $data['is_completed'],
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'reminder_id' => $this->reminderId,
            'title' => $this->title,
            'description' => $this->description,
            'due_at' => $this->dueAt->format(\DATE_RFC3339),
            'completed_at' => $this->completedAt?->format(\DATE_RFC3339),
            'is_completed' => $this->isCompleted,
        ];
    }
}
