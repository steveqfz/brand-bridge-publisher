<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1\Vip;

final readonly class VipManagerLeaderDTO
{
    public function __construct(
        /** Unique identifier for the team leader */
        public string $leaderId,
        /** Full name of the team leader */
        public string $name,
        /** Email address of the team leader */
        public string $email,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            leaderId: (string) $data['leader_id'],
            name: (string) $data['name'],
            email: (string) $data['email'],
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'leader_id' => $this->leaderId,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
