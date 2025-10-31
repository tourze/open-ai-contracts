<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class RateLimited
{
    public function __construct(
        public readonly int $requestsPerMinute = 60,
        public readonly int $tokensPerMinute = 150000,
        public readonly int $requestsPerDay = 1000,
    ) {
    }
}
