<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Tests\Attribute;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\OpenAiContracts\Attribute\OpenAiCompatible;

/**
 * @internal
 */
#[CoversClass(OpenAiCompatible::class)]
class OpenAiCompatibleTest extends TestCase
{
    public function testConstructorWithDefaults(): void
    {
        $attribute = new OpenAiCompatible();

        $this->assertSame('v1', $attribute->version);
        $this->assertFalse($attribute->strictCompatibility);
        $this->assertSame([], $attribute->supportedEndpoints);
    }

    public function testConstructorWithCustomValues(): void
    {
        $endpoints = ['chat/completions', 'models'];
        $attribute = new OpenAiCompatible(
            version: 'v2',
            strictCompatibility: true,
            supportedEndpoints: $endpoints,
        );

        $this->assertSame('v2', $attribute->version);
        $this->assertTrue($attribute->strictCompatibility);
        $this->assertSame($endpoints, $attribute->supportedEndpoints);
    }

    public function testIsAttribute(): void
    {
        $reflectionClass = new \ReflectionClass(OpenAiCompatible::class);
        $attributes = $reflectionClass->getAttributes(\Attribute::class);

        $this->assertCount(1, $attributes);
        $this->assertSame(\Attribute::TARGET_CLASS, $attributes[0]->newInstance()->flags);
    }
}
