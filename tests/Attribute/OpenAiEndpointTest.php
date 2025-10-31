<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Tests\Attribute;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\OpenAiContracts\Attribute\OpenAiEndpoint;

/**
 * @internal
 */
#[CoversClass(OpenAiEndpoint::class)]
class OpenAiEndpointTest extends TestCase
{
    public function testConstructorWithRequiredPath(): void
    {
        $attribute = new OpenAiEndpoint('/v1/chat/completions');

        $this->assertSame('/v1/chat/completions', $attribute->path);
        $this->assertSame('POST', $attribute->method);
        $this->assertSame('', $attribute->name);
        $this->assertSame('', $attribute->description);
    }

    public function testConstructorWithAllParameters(): void
    {
        $attribute = new OpenAiEndpoint(
            path: '/v1/models',
            method: 'GET',
            name: 'list_models',
            description: 'List available models',
        );

        $this->assertSame('/v1/models', $attribute->path);
        $this->assertSame('GET', $attribute->method);
        $this->assertSame('list_models', $attribute->name);
        $this->assertSame('List available models', $attribute->description);
    }

    public function testIsAttribute(): void
    {
        $reflectionClass = new \ReflectionClass(OpenAiEndpoint::class);
        $attributes = $reflectionClass->getAttributes(\Attribute::class);

        $this->assertCount(1, $attributes);
        $expectedFlags = \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD;
        $this->assertSame($expectedFlags, $attributes[0]->newInstance()->flags);
    }
}
