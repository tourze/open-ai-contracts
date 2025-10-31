<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RequiresAuthentication
{
    public function __construct(
        public readonly bool $required = true,
        public readonly string $type = 'bearer',
    ) {
    }
}
