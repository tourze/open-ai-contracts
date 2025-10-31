<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\DTO;

class Balance
{
    /**
     * @param array<string, mixed>|null $details
     */
    public function __construct(
        private readonly float $totalAmount,
        private readonly float $usedAmount,
        private readonly float $remainingAmount,
        private readonly string $currency = 'USD',
        private readonly ?\DateTimeInterface $expiresAt = null,
        private readonly ?array $details = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            totalAmount: self::extractFloatValue($data, 'total_amount', 0.0),
            usedAmount: self::extractFloatValue($data, 'used_amount', 0.0),
            remainingAmount: self::extractFloatValue($data, 'remaining_amount', 0.0),
            currency: self::extractStringValue($data, 'currency', 'USD'),
            expiresAt: self::extractDateTimeValue($data, 'expires_at'),
            details: self::extractArrayValue($data, 'details'),
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function extractFloatValue(array $data, string $key, float $default): float
    {
        if (isset($data[$key]) && (is_float($data[$key]) || is_int($data[$key]) || is_string($data[$key]))) {
            return (float) $data[$key];
        }

        return $default;
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function extractStringValue(array $data, string $key, string $default): string
    {
        if (isset($data[$key]) && is_string($data[$key])) {
            return $data[$key];
        }

        return $default;
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function extractDateTimeValue(array $data, string $key): ?\DateTimeInterface
    {
        if (isset($data[$key]) && is_string($data[$key])) {
            return new \DateTimeImmutable($data[$key]);
        }

        return null;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>|null
     */
    private static function extractArrayValue(array $data, string $key): ?array
    {
        if (isset($data[$key]) && is_array($data[$key])) {
            return $data[$key];
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'total_amount' => $this->totalAmount,
            'used_amount' => $this->usedAmount,
            'remaining_amount' => $this->remainingAmount,
            'currency' => $this->currency,
        ];

        if (null !== $this->expiresAt) {
            $data['expires_at'] = $this->expiresAt->format('Y-m-d H:i:s');
        }

        if (null !== $this->details) {
            $data['details'] = $this->details;
        }

        return $data;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function getUsedAmount(): float
    {
        return $this->usedAmount;
    }

    public function getRemainingAmount(): float
    {
        return $this->remainingAmount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getDetails(): ?array
    {
        return $this->details;
    }
}
