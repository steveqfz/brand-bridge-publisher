<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1\Vip;

final readonly class VipManagerDTO
{
    public function __construct(
        /** Unique identifier for the VIP manager */
        public string $managerId,
        /** Full name of the VIP manager */
        public string $name,
        /** Email address of the VIP manager */
        public string $email,
        /** The manager's team leader, if applicable */
        public ?VipManagerLeaderDTO $leader,
        /** Date the manager was assigned to the player */
        public \DateTimeImmutable $assignedAt,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            managerId: (string) $data['manager_id'],
            name: (string) $data['name'],
            email: (string) $data['email'],
            leader: isset($data['leader']) ? VipManagerLeaderDTO::fromArray($data['leader']) : null,
            assignedAt: new \DateTimeImmutable($data['assigned_at']),
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'manager_id' => $this->managerId,
            'name' => $this->name,
            'email' => $this->email,
            'leader' => $this->leader?->toArray(),
            'assigned_at' => $this->assignedAt->format(\DATE_RFC3339),
        ];
    }
}
