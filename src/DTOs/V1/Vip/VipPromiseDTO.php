<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1\Vip;

final readonly class VipPromiseDTO
{
    public function __construct(
        /** Unique identifier for the promise */
        public string $promiseId,
        /** Promise title */
        public string $title,
        /** Optional detailed description of the promise */
        public ?string $description,
        /** Date the promise was made */
        public \DateTimeImmutable $promisedAt,
        /** Date the promise was fulfilled, null if pending */
        public ?\DateTimeImmutable $fulfilledAt,
        /** Current status of the promise (e.g. 'pending', 'fulfilled', 'broken') */
        public string $status,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            promiseId: (string) $data['promise_id'],
            title: (string) $data['title'],
            description: $data['description'] ?? null,
            promisedAt: new \DateTimeImmutable($data['promised_at']),
            fulfilledAt: isset($data['fulfilled_at']) ? new \DateTimeImmutable($data['fulfilled_at']) : null,
            status: (string) $data['status'],
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'promise_id' => $this->promiseId,
            'title' => $this->title,
            'description' => $this->description,
            'promised_at' => $this->promisedAt->format(\DATE_RFC3339),
            'fulfilled_at' => $this->fulfilledAt?->format(\DATE_RFC3339),
            'status' => $this->status,
        ];
    }
}
