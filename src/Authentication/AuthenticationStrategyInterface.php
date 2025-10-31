<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Authentication;

interface AuthenticationStrategyInterface
{
    /**
     * @param array<string, mixed> $headers
     * @param array<string, mixed> $options
     * @return array{headers: array<string, mixed>, options: array<string, mixed>}
     */
    public function applyAuthentication(array $headers, array $options): array;

    public function handleAuthenticationError(int $statusCode, string $response): void;

    public function isAuthenticationError(int $statusCode): bool;

    public function getAuthenticationErrorMessage(string $response): string;
}
