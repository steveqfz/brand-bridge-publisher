<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1;

use BrandBridge\DTOs\V1\ResponsibleGaming\BettingLimitDTO;
use BrandBridge\DTOs\V1\ResponsibleGaming\SelfExclusionDTO;
use BrandBridge\DTOs\V1\Vip\VipCommentDTO;
use BrandBridge\DTOs\V1\Vip\VipContactDTO;
use BrandBridge\DTOs\V1\Vip\VipGroupDTO;
use BrandBridge\DTOs\V1\Vip\VipManagerDTO;
use BrandBridge\DTOs\V1\Vip\VipMilestoneDTO;
use BrandBridge\DTOs\V1\Vip\VipProfileDTO;
use BrandBridge\DTOs\V1\Vip\VipPromiseDTO;
use BrandBridge\DTOs\V1\Vip\VipReminderDTO;
use BrandBridge\Enums\BrandKey;
use BrandBridge\Enums\PayloadVersion;

final readonly class OnboardingPayload
{
    /**
     * @param list<PlayerTagDTO> $tags
     * @param list<VipCommentDTO> $vipComments
     * @param list<VipMilestoneDTO> $vipMilestones
     * @param list<VipPromiseDTO> $vipPromises
     * @param list<VipReminderDTO> $vipReminders
     * @param list<VipContactDTO> $vipContacts
     */
    public function __construct(
        /** Payload schema version */
        public PayloadVersion $version,
        /** Source brand identifier */
        public BrandKey $sourceBrand,
        /** Unique player identifier within the source brand */
        public string $sourcePlayerId,
        /** Core player profile snapshot */
        public PlayerSnapshot $player,
        /** Extended player details */
        public PlayerDetailsDTO $details,
        /** Player tags collection */
        public array $tags,
        /** Active betting limit, if any */
        public ?BettingLimitDTO $bettingLimit,
        /** Self-exclusion record, if any */
        public ?SelfExclusionDTO $selfExclusion,
        /** VIP profile data, if player is VIP */
        public ?VipProfileDTO $vipProfile,
        /** VIP group assignment, if any */
        public ?VipGroupDTO $vipGroup,
        /** Assigned VIP manager, if any */
        public ?VipManagerDTO $vipManager,
        /** VIP-related comments */
        public array $vipComments,
        /** VIP milestones achieved or in progress */
        public array $vipMilestones,
        /** VIP promises made to the player */
        public array $vipPromises,
        /** VIP reminders for follow-ups */
        public array $vipReminders,
        /** VIP contact methods */
        public array $vipContacts,
        /** Timestamp when this snapshot was taken */
        public \DateTimeImmutable $snapshotTakenAt,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            version: PayloadVersion::from((string) $data['version']),
            sourceBrand: BrandKey::from((string) $data['source_brand']),
            sourcePlayerId: (string) $data['source_player_id'],
            player: PlayerSnapshot::fromArray((array) $data['player']),
            details: PlayerDetailsDTO::fromArray((array) $data['details']),
            tags: array_map(
                static fn(array $tag): PlayerTagDTO => PlayerTagDTO::fromArray($tag),
                (array) $data['tags'],
            ),
            bettingLimit: isset($data['betting_limit']) ? BettingLimitDTO::fromArray($data['betting_limit']) : null,
            selfExclusion: isset($data['self_exclusion']) ? SelfExclusionDTO::fromArray($data['self_exclusion']) : null,
            vipProfile: isset($data['vip_profile']) ? VipProfileDTO::fromArray($data['vip_profile']) : null,
            vipGroup: isset($data['vip_group']) ? VipGroupDTO::fromArray($data['vip_group']) : null,
            vipManager: isset($data['vip_manager']) ? VipManagerDTO::fromArray($data['vip_manager']) : null,
            vipComments: array_map(
                static fn(array $comment): VipCommentDTO => VipCommentDTO::fromArray($comment),
                (array) ($data['vip_comments'] ?? []),
            ),
            vipMilestones: array_map(
                static fn(array $milestone): VipMilestoneDTO => VipMilestoneDTO::fromArray($milestone),
                (array) ($data['vip_milestones'] ?? []),
            ),
            vipPromises: array_map(
                static fn(array $promise): VipPromiseDTO => VipPromiseDTO::fromArray($promise),
                (array) ($data['vip_promises'] ?? []),
            ),
            vipReminders: array_map(
                static fn(array $reminder): VipReminderDTO => VipReminderDTO::fromArray($reminder),
                (array) ($data['vip_reminders'] ?? []),
            ),
            vipContacts: array_map(
                static fn(array $contact): VipContactDTO => VipContactDTO::fromArray($contact),
                (array) ($data['vip_contacts'] ?? []),
            ),
            snapshotTakenAt: new \DateTimeImmutable($data['snapshot_taken_at']),
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'version' => $this->version->value,
            'source_brand' => $this->sourceBrand->value,
            'source_player_id' => $this->sourcePlayerId,
            'player' => $this->player->toArray(),
            'details' => $this->details->toArray(),
            'tags' => array_map(
                static fn(PlayerTagDTO $tag): array => $tag->toArray(),
                $this->tags,
            ),
            'betting_limit' => $this->bettingLimit?->toArray(),
            'self_exclusion' => $this->selfExclusion?->toArray(),
            'vip_profile' => $this->vipProfile?->toArray(),
            'vip_group' => $this->vipGroup?->toArray(),
            'vip_manager' => $this->vipManager?->toArray(),
            'vip_comments' => array_map(
                static fn(VipCommentDTO $comment): array => $comment->toArray(),
                $this->vipComments,
            ),
            'vip_milestones' => array_map(
                static fn(VipMilestoneDTO $milestone): array => $milestone->toArray(),
                $this->vipMilestones,
            ),
            'vip_promises' => array_map(
                static fn(VipPromiseDTO $promise): array => $promise->toArray(),
                $this->vipPromises,
            ),
            'vip_reminders' => array_map(
                static fn(VipReminderDTO $reminder): array => $reminder->toArray(),
                $this->vipReminders,
            ),
            'vip_contacts' => array_map(
                static fn(VipContactDTO $contact): array => $contact->toArray(),
                $this->vipContacts,
            ),
            'snapshot_taken_at' => $this->snapshotTakenAt->format(\DATE_RFC3339),
        ];
    }

    /**
     * Computes a SHA-256 fingerprint of the payload for change detection.
     * Arrays are sorted to ensure deterministic output regardless of ordering.
     */
    public function fingerprint(): string
    {
        $data = $this->toArray();

        $this->sortArraysRecursively($data);

        return hash('sha256', json_encode($data, \JSON_THROW_ON_ERROR));
    }

    /** @param array<string, mixed> $data */
    private function sortArraysRecursively(array &$data): void
    {
        foreach ($data as &$value) {
            if (is_array($value)) {
                if ($this->isAssociative($value)) {
                    ksort($value);
                } else {
                    sort($value);
                }
                $this->sortArraysRecursively($value);
            }
        }
    }

    /** @param array<mixed> $array */
    private function isAssociative(array $array): bool
    {
        if ($array === []) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }
}
