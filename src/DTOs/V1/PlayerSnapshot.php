<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1;

use BrandBridge\Enums\BrandKey;

final readonly class PlayerSnapshot
{
    public function __construct(
        /** Unique player identifier within the source brand */
        public string $sourcePlayerId,
        /** Brand this player belongs to */
        public BrandKey $sourceBrand,
        /** Player email address */
        public string $email,
        /** Player username */
        public string $username,
        /** Player first name */
        public ?string $firstName,
        /** Player last name */
        public ?string $lastName,
        /** Player date of birth */
        public ?\DateTimeImmutable $dateOfBirth,
        /** ISO 3166-1 alpha-2 country code */
        public string $country,
        /** Player phone number */
        public ?string $phoneNumber,
        /** Account registration date */
        public \DateTimeImmutable $registeredAt,
        /** Last login timestamp */
        public ?\DateTimeImmutable $lastLoginAt,
        /** KYC verification reference */
        public ?string $kycReference,
        /** ISO 4217 currency code */
        public string $currency,
        /** Preferred language code */
        public ?string $language,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            sourcePlayerId: (string) $data['source_player_id'],
            sourceBrand: BrandKey::from((string) $data['source_brand']),
            email: (string) $data['email'],
            username: (string) $data['username'],
            firstName: $data['first_name'] ?? null,
            lastName: $data['last_name'] ?? null,
            dateOfBirth: isset($data['date_of_birth']) ? new \DateTimeImmutable($data['date_of_birth']) : null,
            country: (string) $data['country'],
            phoneNumber: $data['phone_number'] ?? null,
            registeredAt: new \DateTimeImmutable($data['registered_at']),
            lastLoginAt: isset($data['last_login_at']) ? new \DateTimeImmutable($data['last_login_at']) : null,
            kycReference: $data['kyc_reference'] ?? null,
            currency: (string) $data['currency'],
            language: $data['language'] ?? null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'source_player_id' => $this->sourcePlayerId,
            'source_brand' => $this->sourceBrand->value,
            'email' => $this->email,
            'username' => $this->username,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'date_of_birth' => $this->dateOfBirth?->format('Y-m-d'),
            'country' => $this->country,
            'phone_number' => $this->phoneNumber,
            'registered_at' => $this->registeredAt->format(\DATE_RFC3339),
            'last_login_at' => $this->lastLoginAt?->format(\DATE_RFC3339),
            'kyc_reference' => $this->kycReference,
            'currency' => $this->currency,
            'language' => $this->language,
        ];
    }
}
