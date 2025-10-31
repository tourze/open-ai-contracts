<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Provider;

use Tourze\OpenAiContracts\Client\OpenAiCompatibleClientInterface;

interface OpenAiClientProviderInterface
{
    final public const TAG_NAME = 'open_ai.client_provider';

    /**
     * @return iterable<OpenAiCompatibleClientInterface>
     */
    public function fetchOpenAiClient(): iterable;
}
