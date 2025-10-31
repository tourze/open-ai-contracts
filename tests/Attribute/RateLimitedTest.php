<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Tests\Attribute;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\OpenAiContracts\Attribute\RateLimited;

/**
 * @internal
 */
#[CoversClass(RateLimited::class)]
class RateLimitedTest extends TestCase
{
    public function testConstructorWithDefaultsShouldSetCorrectValues(): void
    {
        $attribute = new RateLimited();

        $this->assertSame(60, $attribute->requestsPerMinute);
        $this->assertSame(150000, $attribute->tokensPerMinute);
        $this->assertSame(1000, $attribute->requestsPerDay);
    }

    public function testConstructorWithCustomValuesShouldSetCorrectValues(): void
    {
        $attribute = new RateLimited(
            requestsPerMinute: 120,
            tokensPerMinute: 300000,
            requestsPerDay: 2000,
        );

        $this->assertSame(120, $attribute->requestsPerMinute);
        $this->assertSame(300000, $attribute->tokensPerMinute);
        $this->assertSame(2000, $attribute->requestsPerDay);
    }

    public function testConstructorWithPartialParametersShouldUseDefaults(): void
    {
        $attribute = new RateLimited(requestsPerMinute: 90);

        $this->assertSame(90, $attribute->requestsPerMinute);
        $this->assertSame(150000, $attribute->tokensPerMinute);
        $this->assertSame(1000, $attribute->requestsPerDay);
    }

    public function testConstructorWithZeroValuesShouldAcceptValues(): void
    {
        $attribute = new RateLimited(
            requestsPerMinute: 0,
            tokensPerMinute: 0,
            requestsPerDay: 0,
        );

        $this->assertSame(0, $attribute->requestsPerMinute);
        $this->assertSame(0, $attribute->tokensPerMinute);
        $this->assertSame(0, $attribute->requestsPerDay);
    }

    public function testConstructorWithHighValuesShouldAcceptValues(): void
    {
        $attribute = new RateLimited(
            requestsPerMinute: 999999,
            tokensPerMinute: 999999999,
            requestsPerDay: 999999,
        );

        $this->assertSame(999999, $attribute->requestsPerMinute);
        $this->assertSame(999999999, $attribute->tokensPerMinute);
        $this->assertSame(999999, $attribute->requestsPerDay);
    }

    public function testIsAttributeShouldHaveCorrectTargets(): void
    {
        $reflectionClass = new \ReflectionClass(RateLimited::class);
        $attributes = $reflectionClass->getAttributes(\Attribute::class);

        $this->assertCount(1, $attributes);
        $this->assertSame(
            \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD,
            $attributes[0]->newInstance()->flags
        );
    }

    public function testCanBeUsedAsClassAttributeShouldWork(): void
    {
        $reflection = new \ReflectionClass(RateLimited::class);
        $attributes = $reflection->getAttributes(\Attribute::class);
        $attributeInstance = $attributes[0]->newInstance();

        $this->assertTrue(($attributeInstance->flags & \Attribute::TARGET_CLASS) !== 0);
    }

    public function testCanBeUsedAsMethodAttributeShouldWork(): void
    {
        $reflection = new \ReflectionClass(RateLimited::class);
        $attributes = $reflection->getAttributes(\Attribute::class);
        $attributeInstance = $attributes[0]->newInstance();

        $this->assertTrue(($attributeInstance->flags & \Attribute::TARGET_METHOD) !== 0);
    }
}
