<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1;

use BrandBridge\Enums\PlayerStatus;

final readonly class PlayerDetailsDTO
{
    public function __construct(
        /** Player gender */
        public ?string $gender,
        /** Player street address */
        public ?string $address,
        /** Player city */
        public ?string $city,
        /** Player postal/zip code */
        public ?string $postalCode,
        /** Player region/state/province */
        public ?string $region,
        /** Player account status */
        public PlayerStatus $status,
        /** Total lifetime deposits in player currency */
        public float $totalDeposits,
        /** Total lifetime withdrawals in player currency */
        public float $totalWithdrawals,
        /** Net revenue (deposits minus withdrawals) in player currency */
        public float $netRevenue,
        /** Timestamp of the player's last deposit */
        public ?\DateTimeImmutable $lastDepositAt,
        /** Date the player achieved VIP status */
        public ?\DateTimeImmutable $vipSince,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            gender: $data['gender'] ?? null,
            address: $data['address'] ?? null,
            city: $data['city'] ?? null,
            postalCode: $data['postal_code'] ?? null,
            region: $data['region'] ?? null,
            status: PlayerStatus::from((string) $data['status']),
            totalDeposits: (float) $data['total_deposits'],
            totalWithdrawals: (float) $data['total_withdrawals'],
            netRevenue: (float) $data['net_revenue'],
            lastDepositAt: isset($data['last_deposit_at']) ? new \DateTimeImmutable($data['last_deposit_at']) : null,
            vipSince: isset($data['vip_since']) ? new \DateTimeImmutable($data['vip_since']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'gender' => $this->gender,
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postalCode,
            'region' => $this->region,
            'status' => $this->status->value,
            'total_deposits' => $this->totalDeposits,
            'total_withdrawals' => $this->totalWithdrawals,
            'net_revenue' => $this->netRevenue,
            'last_deposit_at' => $this->lastDepositAt?->format(\DATE_RFC3339),
            'vip_since' => $this->vipSince?->format(\DATE_RFC3339),
        ];
    }
}
