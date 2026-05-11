<?php

declare(strict_types=1);

namespace BrandBridge\DTOs\V1\ResponsibleGaming;

final readonly class BettingLimitDTO
{
    public function __construct(
        /** Type of betting limit (e.g. 'daily', 'weekly', 'monthly') */
        public string $limitType,
        /** Maximum allowed amount for the limit period */
        public float $amount,
        /** ISO 4217 currency code for the limit amount */
        public string $currency,
        /** Date from which the limit takes effect */
        public \DateTimeImmutable $effectiveFrom,
        /** Date until which the limit is active, null if indefinite */
        public ?\DateTimeImmutable $effectiveUntil,
    ) {}

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            limitType: (string) $data['limit_type'],
            amount: (float) $data['amount'],
            currency: (string) $data['currency'],
            effectiveFrom: new \DateTimeImmutable($data['effective_from']),
            effectiveUntil: isset($data['effective_until']) ? new \DateTimeImmutable($data['effective_until']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'limit_type' => $this->limitType,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'effective_from' => $this->effectiveFrom->format(\DATE_RFC3339),
            'effective_until' => $this->effectiveUntil?->format(\DATE_RFC3339),
        ];
    }
}
