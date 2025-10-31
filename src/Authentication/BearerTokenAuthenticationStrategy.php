<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Authentication;

abstract class BearerTokenAuthenticationStrategy implements AuthenticationStrategyInterface
{
    /**
     * @param array<string, mixed> $headers
     * @param array<string, mixed> $options
     * @return array{headers: array<string, mixed>, options: array<string, mixed>}
     */
    public function applyAuthentication(array $headers, array $options): array
    {
        $headers['Authorization'] = 'Bearer ' . $this->getApiKey();

        return [
            'headers' => $headers,
            'options' => $options,
        ];
    }

    abstract protected function getApiKey(): string;

    public function handleAuthenticationError(int $statusCode, string $response): void
    {
        if ($this->isAuthenticationError($statusCode)) {
            $this->onAuthenticationError();
        }
    }

    public function isAuthenticationError(int $statusCode): bool
    {
        return 401 === $statusCode || 403 === $statusCode;
    }

    abstract protected function onAuthenticationError(): void;

    public function getAuthenticationErrorMessage(string $response): string
    {
        $data = json_decode($response, true);

        if (!is_array($data)) {
            return 'Authentication failed';
        }

        if (isset($data['error']) && is_array($data['error']) && isset($data['error']['message']) && is_string($data['error']['message'])) {
            return $data['error']['message'];
        }

        if (isset($data['message']) && is_string($data['message'])) {
            return $data['message'];
        }

        return 'Authentication failed';
    }
}
