<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1;

final readonly class PlayerDetailsDTO
{
    public function __construct(
       /** Player first name */
        public ?string $firstName,

        /** Player last name */
        public ?string $lastName,

        /** Player middle name */
        public ?string $middleName,

        /** International calling code */
        public ?string $callingCode,

        /** Contact number */
        public ?string $contactNumber,

        /** Player birthday */
        public ?\DateTimeImmutable $birthday,

        /** Preferred language */
        public ?string $language,

        /** Full address */
        public ?string $address,

        /** Postal / ZIP code */
        public ?string $postCode,

        /** Player nationality */
        public ?string $nationality,

        /** Player gender */
        public ?string $gender,

        /** Internal player ID */
        public int|string|null $playerId,

        /** Signup source URL */
        public ?string $signupUrl,

        /** Country ID */
        public int|string|null $countryId,

        /** State / province ID */
        public int|string|null $stateId,

        /** City ID */
        public int|string|null $cityId,

        /** Additional payload data */
        public ?array $payload,

        /** Registration source */
        public ?string $registerFrom,

        /** Query string used during signup */
        public ?string $queryString,

        /** State / province name */
        public ?string $stateName,

        /** City name */
        public ?string $cityName,

        /** Player timezone */
        public ?string $timezone,

        /** Private mode enabled */
        public bool $privateMode,

        /** Politically exposed person */
        public bool $isPep,

        /** Sanctions flag */
        public bool $hasSanctions,

        /** Custom attributes */
        public ?array $customAttributes,

        /** Risk / behavior indicators */
        public ?array $indicators,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            firstName: $data['first_name'] ?? null,
            lastName: $data['last_name'] ?? null,
            middleName: $data['middle_name'] ?? null,
            callingCode: $data['calling_code'] ?? null,
            contactNumber: $data['contact_number'] ?? null,
            birthday: isset($data['birthday'])
                ? new \DateTimeImmutable($data['birthday'])
                : null,
            language: $data['language'] ?? null,
            address: $data['address'] ?? null,
            postCode: $data['post_code'] ?? null,
            nationality: $data['nationality'] ?? null,
            gender: $data['gender'] ?? null,
            playerId: $data['player_id'] ?? null,
            signupUrl: $data['signup_url'] ?? null,
            countryId: $data['country_id'] ?? null,
            stateId: $data['state_id'] ?? null,
            cityId: $data['city_id'] ?? null,
            payload: isset($data['payload'])
                ? (array) $data['payload']
                : null,
            registerFrom: $data['register_from'] ?? null,
            queryString: $data['query_string'] ?? null,
            stateName: $data['state_name'] ?? null,
            cityName: $data['city_name'] ?? null,
            timezone: $data['timezone'] ?? null,
            privateMode: (bool) ($data['private_mode'] ?? false),
            isPep: (bool) ($data['is_pep'] ?? false),
            hasSanctions: (bool) ($data['has_sanctions'] ?? false),
            customAttributes: isset($data['custom_attributes'])
                ? (array) $data['custom_attributes']
                : null,
            indicators: isset($data['indicators'])
                ? (array) $data['indicators']
                : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'middle_name' => $this->middleName,
            'calling_code' => $this->callingCode,
            'contact_number' => $this->contactNumber,
            'birthday' => $this->birthday?->format('Y-m-d'),
            'language' => $this->language,
            'address' => $this->address,
            'post_code' => $this->postCode,
            'nationality' => $this->nationality,
            'gender' => $this->gender,
            'player_id' => $this->playerId,
            'signup_url' => $this->signupUrl,
            'country_id' => $this->countryId,
            'state_id' => $this->stateId,
            'city_id' => $this->cityId,
            'payload' => $this->payload,
            'register_from' => $this->registerFrom,
            'query_string' => $this->queryString,
            'state_name' => $this->stateName,
            'city_name' => $this->cityName,
            'timezone' => $this->timezone,
            'private_mode' => $this->privateMode,
            'is_pep' => $this->isPep,
            'has_sanctions' => $this->hasSanctions,
            'custom_attributes' => $this->customAttributes,
            'indicators' => $this->indicators,
        ];
    }
}
