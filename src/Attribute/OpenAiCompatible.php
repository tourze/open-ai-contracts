<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class OpenAiCompatible
{
    /**
     * @param string[] $supportedEndpoints
     */
    public function __construct(
        public readonly string $version = 'v1',
        public readonly bool $strictCompatibility = false,
        public readonly array $supportedEndpoints = [],
    ) {
    }
}
