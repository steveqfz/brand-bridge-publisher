<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1;

final readonly class OauthPlayersDTO
{
    public function __construct(
        /** Internal player ID */
        public int|string $playerId,
        /** Provider player identifier */
        public string $providerPlayerId,
        /** OAuth provider name */
        public string $provider,
        /** Additional provider metadata */
        public ?array $meta,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            playerId: $data['player_id'],
            providerPlayerId: (string) $data['provider_player_id'],
            provider: (string) $data['provider'],
            meta: isset($data['meta'])
                ? (array) $data['meta']
                : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'player_id' => $this->playerId,
            'provider_player_id' => $this->providerPlayerId,
            'provider' => $this->provider,
            'meta' => $this->meta,
        ];
    }
}
