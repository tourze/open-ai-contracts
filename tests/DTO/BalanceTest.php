<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Tests\DTO;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\OpenAiContracts\DTO\Balance;

/**
 * @internal
 */
#[CoversClass(Balance::class)]
class BalanceTest extends TestCase
{
    public function testConstructWithMinimalParametersShouldCreateInstance(): void
    {
        $balance = new Balance(
            totalAmount: 100.0,
            usedAmount: 30.0,
            remainingAmount: 70.0
        );

        $this->assertSame(100.0, $balance->getTotalAmount());
        $this->assertSame(30.0, $balance->getUsedAmount());
        $this->assertSame(70.0, $balance->getRemainingAmount());
        $this->assertSame('USD', $balance->getCurrency());
        $this->assertNull($balance->getExpiresAt());
        $this->assertNull($balance->getDetails());
    }

    public function testConstructWithAllParametersShouldCreateInstance(): void
    {
        $expiresAt = new \DateTimeImmutable('2024-12-31 23:59:59');
        $details = ['plan' => 'premium', 'region' => 'us-east-1'];

        $balance = new Balance(
            totalAmount: 500.0,
            usedAmount: 150.0,
            remainingAmount: 350.0,
            currency: 'EUR',
            expiresAt: $expiresAt,
            details: $details
        );

        $this->assertSame(500.0, $balance->getTotalAmount());
        $this->assertSame(150.0, $balance->getUsedAmount());
        $this->assertSame(350.0, $balance->getRemainingAmount());
        $this->assertSame('EUR', $balance->getCurrency());
        $this->assertSame($expiresAt, $balance->getExpiresAt());
        $this->assertSame($details, $balance->getDetails());
    }

    public function testFromArrayWithMinimalDataShouldCreateInstance(): void
    {
        $data = [
            'total_amount' => 200.0,
            'used_amount' => 80.0,
            'remaining_amount' => 120.0,
        ];

        $balance = Balance::fromArray($data);

        $this->assertSame(200.0, $balance->getTotalAmount());
        $this->assertSame(80.0, $balance->getUsedAmount());
        $this->assertSame(120.0, $balance->getRemainingAmount());
        $this->assertSame('USD', $balance->getCurrency());
        $this->assertNull($balance->getExpiresAt());
        $this->assertNull($balance->getDetails());
    }

    public function testFromArrayWithCompleteDataShouldCreateInstance(): void
    {
        $data = [
            'total_amount' => 1000.0,
            'used_amount' => 250.0,
            'remaining_amount' => 750.0,
            'currency' => 'GBP',
            'expires_at' => '2024-06-30 12:00:00',
            'details' => ['tier' => 'enterprise', 'features' => ['api', 'support']],
        ];

        $balance = Balance::fromArray($data);

        $this->assertSame(1000.0, $balance->getTotalAmount());
        $this->assertSame(250.0, $balance->getUsedAmount());
        $this->assertSame(750.0, $balance->getRemainingAmount());
        $this->assertSame('GBP', $balance->getCurrency());
        $this->assertInstanceOf(\DateTimeInterface::class, $balance->getExpiresAt());
        $this->assertSame('2024-06-30 12:00:00', $balance->getExpiresAt()->format('Y-m-d H:i:s'));
        $this->assertSame(['tier' => 'enterprise', 'features' => ['api', 'support']], $balance->getDetails());
    }

    public function testFromArrayWithMissingValuesShouldUseDefaults(): void
    {
        $data = [];

        $balance = Balance::fromArray($data);

        $this->assertSame(0.0, $balance->getTotalAmount());
        $this->assertSame(0.0, $balance->getUsedAmount());
        $this->assertSame(0.0, $balance->getRemainingAmount());
        $this->assertSame('USD', $balance->getCurrency());
        $this->assertNull($balance->getExpiresAt());
        $this->assertNull($balance->getDetails());
    }

    public function testToArrayWithMinimalDataShouldReturnCorrectArray(): void
    {
        $balance = new Balance(
            totalAmount: 300.0,
            usedAmount: 100.0,
            remainingAmount: 200.0
        );

        $expected = [
            'total_amount' => 300.0,
            'used_amount' => 100.0,
            'remaining_amount' => 200.0,
            'currency' => 'USD',
        ];

        $this->assertSame($expected, $balance->toArray());
    }

    public function testToArrayWithCompleteDataShouldReturnCorrectArray(): void
    {
        $expiresAt = new \DateTimeImmutable('2024-03-15 10:30:45');
        $details = ['subscription' => 'annual', 'discount' => 0.1];

        $balance = new Balance(
            totalAmount: 800.0,
            usedAmount: 200.0,
            remainingAmount: 600.0,
            currency: 'CAD',
            expiresAt: $expiresAt,
            details: $details
        );

        $expected = [
            'total_amount' => 800.0,
            'used_amount' => 200.0,
            'remaining_amount' => 600.0,
            'currency' => 'CAD',
            'expires_at' => '2024-03-15 10:30:45',
            'details' => ['subscription' => 'annual', 'discount' => 0.1],
        ];

        $this->assertSame($expected, $balance->toArray());
    }

    public function testSerializationRoundTripShouldPreserveData(): void
    {
        $originalData = [
            'total_amount' => 1500.0,
            'used_amount' => 500.0,
            'remaining_amount' => 1000.0,
            'currency' => 'JPY',
            'expires_at' => '2024-09-20 14:25:30',
            'details' => ['plan' => 'business', 'features' => ['priority_support']],
        ];

        $balance = Balance::fromArray($originalData);
        $serializedData = $balance->toArray();
        $reconstructedBalance = Balance::fromArray($serializedData);

        $this->assertSame($balance->getTotalAmount(), $reconstructedBalance->getTotalAmount());
        $this->assertSame($balance->getUsedAmount(), $reconstructedBalance->getUsedAmount());
        $this->assertSame($balance->getRemainingAmount(), $reconstructedBalance->getRemainingAmount());
        $this->assertSame($balance->getCurrency(), $reconstructedBalance->getCurrency());
        $this->assertEquals($balance->getExpiresAt(), $reconstructedBalance->getExpiresAt());
        $this->assertSame($balance->getDetails(), $reconstructedBalance->getDetails());
    }

    public function testFromArrayWithInvalidDateFormatShouldThrowException(): void
    {
        $data = [
            'total_amount' => 100.0,
            'used_amount' => 50.0,
            'remaining_amount' => 50.0,
            'expires_at' => 'invalid-date-format',
        ];

        $this->expectException(\Throwable::class);
        Balance::fromArray($data);
    }

    public function testGetterMethodsShouldReturnCorrectValues(): void
    {
        $expiresAt = new \DateTimeImmutable('2024-01-01 00:00:00');
        $details = ['test' => 'value'];

        $balance = new Balance(
            totalAmount: 999.99,
            usedAmount: 111.11,
            remainingAmount: 888.88,
            currency: 'AUD',
            expiresAt: $expiresAt,
            details: $details
        );

        $this->assertSame(999.99, $balance->getTotalAmount());
        $this->assertSame(111.11, $balance->getUsedAmount());
        $this->assertSame(888.88, $balance->getRemainingAmount());
        $this->assertSame('AUD', $balance->getCurrency());
        $this->assertSame($expiresAt, $balance->getExpiresAt());
        $this->assertSame($details, $balance->getDetails());
    }
}
