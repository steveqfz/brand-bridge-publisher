<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1;

final readonly class EligibilityResult
{
    /**
     * @param list<string> $reasons
     */
    public function __construct(
        /** Whether the player is eligible for onboarding */
        public bool $eligible,
        /** List of reasons explaining the eligibility decision */
        public array $reasons,
        /** Timestamp when the eligibility check was performed */
        public \DateTimeImmutable $checkedAt,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            eligible: (bool) $data['eligible'],
            reasons: array_map(strval(...), (array) $data['reasons']),
            checkedAt: new \DateTimeImmutable($data['checked_at']),
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'eligible' => $this->eligible,
            'reasons' => $this->reasons,
            'checked_at' => $this->checkedAt->format(\DATE_RFC3339),
        ];
    }
}
