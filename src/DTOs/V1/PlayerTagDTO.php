<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1;

final readonly class PlayerTagDTO
{
    public function __construct(
        /** Tag name identifier */
        public string $name,
        /** Tag value */
        public string $value,
        /** Timestamp when the tag was assigned to the player */
        public \DateTimeImmutable $assignedAt,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) $data['name'],
            value: (string) $data['value'],
            assignedAt: new \DateTimeImmutable($data['assigned_at']),
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
            'assigned_at' => $this->assignedAt->format(\DATE_RFC3339),
        ];
    }
}
