<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1;

use BrandBridge\Enums\BrandKey;
use BrandBridge\Enums\PlayerStatus;

final readonly class PlayerSnapshot
{
    public function __construct(
        /** Unique player identifier within the source brand */
        public string $sourcePlayerId,
        /** Brand this player belongs to */
        public BrandKey $sourceBrand,

        public ?int $vipSettingId,
        public string $username,
        public string $email,
        public ?string $password,
        public string $currencyCode,
        public PlayerStatus $status,
        public bool $banned,
        public bool $excluded,
        public bool $suspended,
        public ?\DateTimeImmutable $kickedAt,
        public ?\DateTimeImmutable $lastLoggedInAt,
        public ?\DateTimeImmutable $phoneVerifiedAt,
        public ?float $betLimit,
        public bool $isTestPlayer,
        public ?string $agentCode,
        public ?string $batchId,
        public ?string $phoneNumber,
        public bool $isHoldWithdrawal,
        public string $uuid,
        public ?\DateTimeImmutable $lastOnlineAt,
        public ?\DateTimeImmutable $lastOfflineAt,
        public ?int $referredById,
        public ?string $referredByCode,
        public ?string $referredOrderNumber,
        public ?string $preferredCurrency,
        public bool $passwordInitiated,
        public ?int $vipRewardSettingId,
        public ?string $globalId,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            sourcePlayerId: (string) $data['source_player_id'],
            sourceBrand: BrandKey::from((string) $data['source_brand']),
            vipSettingId: $data['vip_setting_id'] ?? null,
            username: (string) $data['username'],
            email: (string) $data['email'],
            password: $data['password'] ?? null,
            currencyCode: (string) $data['currency_code'],
            status: PlayerStatus::from((string) $data['status']),
            banned: (bool) ($data['banned'] ?? false),
            excluded: (bool) ($data['excluded'] ?? false),
            suspended: (bool) ($data['suspended'] ?? false),

            kickedAt: isset($data['kicked_at'])
                ? new \DateTimeImmutable($data['kicked_at'])
                : null,

            lastLoggedInAt: isset($data['last_logged_in_at'])
                ? new \DateTimeImmutable($data['last_logged_in_at'])
                : null,

            phoneVerifiedAt: isset($data['phone_verified_at'])
                ? new \DateTimeImmutable($data['phone_verified_at'])
                : null,

            betLimit: isset($data['bet_limit'])
                ? (float) $data['bet_limit']
                : null,

            isTestPlayer: (bool) ($data['is_test_player'] ?? false),
            agentCode: $data['agent_code'] ?? null,
            batchId: $data['batch_id'] ?? null,
            phoneNumber: $data['phone_number'] ?? null,
            isHoldWithdrawal: (bool) ($data['is_hold_withdrawal'] ?? false),
            uuid: (string) $data['uuid'],

            lastOnlineAt: isset($data['last_online_at'])
                ? new \DateTimeImmutable($data['last_online_at'])
                : null,

            lastOfflineAt: isset($data['last_offline_at'])
                ? new \DateTimeImmutable($data['last_offline_at'])
                : null,

            referredById: $data['referred_by_id'] ?? null,
            referredByCode: $data['referred_by_code'] ?? null,
            referredOrderNumber: $data['referred_order_number'] ?? null,
            preferredCurrency: $data['preferred_currency'] ?? null,
            passwordInitiated: (bool) ($data['password_initiated'] ?? false),
            vipRewardSettingId: $data['vip_reward_setting_id'] ?? null,
            globalId: $data['global_id'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'source_player_id' => $this->sourcePlayerId,
            'source_brand' => $this->sourceBrand->value,
            'vip_setting_id' => $this->vipSettingId,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'currency_code' => $this->currencyCode,
            'status' => $this->status->value,
            'banned' => $this->banned,
            'excluded' => $this->excluded,
            'suspended' => $this->suspended,
            'kicked_at' => $this->kickedAt?->format(\DATE_RFC3339),
            'last_logged_in_at' => $this->lastLoggedInAt?->format(\DATE_RFC3339),
            'phone_verified_at' => $this->phoneVerifiedAt?->format(\DATE_RFC3339),
            'bet_limit' => $this->betLimit,
            'is_test_player' => $this->isTestPlayer,
            'agent_code' => $this->agentCode,
            'batch_id' => $this->batchId,
            'phone_number' => $this->phoneNumber,
            'is_hold_withdrawal' => $this->isHoldWithdrawal,
            'uuid' => $this->uuid,
            'last_online_at' => $this->lastOnlineAt?->format(\DATE_RFC3339),
            'last_offline_at' => $this->lastOfflineAt?->format(\DATE_RFC3339),
            'referred_by_id' => $this->referredById,
            'referred_by_code' => $this->referredByCode,
            'referred_order_number' => $this->referredOrderNumber,
            'preferred_currency' => $this->preferredCurrency,
            'password_initiated' => $this->passwordInitiated,
            'vip_reward_setting_id' => $this->vipRewardSettingId,
            'global_id' => $this->globalId,
        ];
    }
}
