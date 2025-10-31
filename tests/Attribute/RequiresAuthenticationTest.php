<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Tests\Attribute;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\OpenAiContracts\Attribute\RequiresAuthentication;

/**
 * @internal
 */
#[CoversClass(RequiresAuthentication::class)]
class RequiresAuthenticationTest extends TestCase
{
    public function testConstructorWithDefaultsShouldSetCorrectValues(): void
    {
        $attribute = new RequiresAuthentication();

        $this->assertTrue($attribute->required);
        $this->assertSame('bearer', $attribute->type);
    }

    public function testConstructorWithCustomValuesShouldSetCorrectValues(): void
    {
        $attribute = new RequiresAuthentication(
            required: false,
            type: 'api-key',
        );

        $this->assertFalse($attribute->required);
        $this->assertSame('api-key', $attribute->type);
    }

    public function testConstructorWithPartialParametersShouldUseDefaults(): void
    {
        $attribute = new RequiresAuthentication(required: false);

        $this->assertFalse($attribute->required);
        $this->assertSame('bearer', $attribute->type);
    }

    public function testConstructorWithTrueRequiredShouldWork(): void
    {
        $attribute = new RequiresAuthentication(required: true);

        $this->assertTrue($attribute->required);
        $this->assertSame('bearer', $attribute->type);
    }

    public function testConstructorWithDifferentTypesStringShouldWork(): void
    {
        $types = ['bearer', 'api-key', 'oauth', 'basic', 'custom'];

        foreach ($types as $type) {
            $attribute = new RequiresAuthentication(type: $type);

            $this->assertSame($type, $attribute->type);
            $this->assertTrue($attribute->required);
        }
    }

    public function testConstructorWithEmptyTypeShouldAcceptEmptyString(): void
    {
        $attribute = new RequiresAuthentication(type: '');

        $this->assertSame('', $attribute->type);
        $this->assertTrue($attribute->required);
    }

    public function testConstructorWithBothParametersFalseShouldWork(): void
    {
        $attribute = new RequiresAuthentication(
            required: false,
            type: 'none',
        );

        $this->assertFalse($attribute->required);
        $this->assertSame('none', $attribute->type);
    }

    public function testIsAttributeShouldHaveCorrectTargets(): void
    {
        $reflectionClass = new \ReflectionClass(RequiresAuthentication::class);
        $attributes = $reflectionClass->getAttributes(\Attribute::class);

        $this->assertCount(1, $attributes);
        $this->assertSame(
            \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD,
            $attributes[0]->newInstance()->flags
        );
    }

    public function testCanBeUsedAsClassAttributeShouldWork(): void
    {
        $reflection = new \ReflectionClass(RequiresAuthentication::class);
        $attributes = $reflection->getAttributes(\Attribute::class);
        $attributeInstance = $attributes[0]->newInstance();

        $this->assertTrue(($attributeInstance->flags & \Attribute::TARGET_CLASS) !== 0);
    }

    public function testCanBeUsedAsMethodAttributeShouldWork(): void
    {
        $reflection = new \ReflectionClass(RequiresAuthentication::class);
        $attributes = $reflection->getAttributes(\Attribute::class);
        $attributeInstance = $attributes[0]->newInstance();

        $this->assertTrue(($attributeInstance->flags & \Attribute::TARGET_METHOD) !== 0);
    }
}
