<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Exception;

interface AuthenticationExceptionInterface extends OpenAiExceptionInterface
{
    public function getAuthenticationError(): ?string;

    public function requiresNewApiKey(): bool;
}
