<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1;

use BrandBridge\Enums\BrandKey;
use BrandBridge\Enums\PayloadVersion;

final readonly class OnboardingPayload
{
    /**
     * @param list<PlayerTagDTO> $tags
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
