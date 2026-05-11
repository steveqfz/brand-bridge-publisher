<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1\Vip;

final readonly class VipGroupDTO
{
    public function __construct(
        /** Unique identifier for the VIP group */
        public string $groupId,
        /** Display name of the VIP group */
        public string $name,
        /** Optional description of the VIP group purpose */
        public ?string $description,
        /** Group configuration settings */
        public ?VipGroupSettingsDTO $settings,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            groupId: (string) $data['group_id'],
            name: (string) $data['name'],
            description: $data['description'] ?? null,
            settings: isset($data['settings']) ? VipGroupSettingsDTO::fromArray($data['settings']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'group_id' => $this->groupId,
            'name' => $this->name,
            'description' => $this->description,
            'settings' => $this->settings?->toArray(),
        ];
    }
}
