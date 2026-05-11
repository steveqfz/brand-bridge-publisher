<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1\Vip;

final readonly class VipContactDTO
{
    public function __construct(
        /** Unique identifier for the contact entry */
        public string $contactId,
        /** Contact type (e.g. 'phone', 'email', 'chat') */
        public string $type,
        /** Contact value (phone number, email address, handle) */
        public string $value,
        /** Whether this is the primary contact method */
        public bool $isPrimary,
        /** Timestamp when the contact was created */
        public \DateTimeImmutable $createdAt,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            contactId: (string) $data['contact_id'],
            type: (string) $data['type'],
            value: (string) $data['value'],
            isPrimary: (bool) $data['is_primary'],
            createdAt: new \DateTimeImmutable($data['created_at']),
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'contact_id' => $this->contactId,
            'type' => $this->type,
            'value' => $this->value,
            'is_primary' => $this->isPrimary,
            'created_at' => $this->createdAt->format(\DATE_RFC3339),
        ];
    }
}
