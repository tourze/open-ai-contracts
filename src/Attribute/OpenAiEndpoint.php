<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class OpenAiEndpoint
{
    public function __construct(
        public readonly string $path,
        public readonly string $method = 'POST',
        public readonly string $name = '',
        public readonly string $description = '',
    ) {
    }
}
