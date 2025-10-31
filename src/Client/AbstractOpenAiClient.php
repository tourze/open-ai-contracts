<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Client;

use Tourze\OpenAiContracts\Authentication\AuthenticationStrategyInterface;
use Tourze\OpenAiContracts\Request\ChatCompletionRequestInterface;
use Tourze\OpenAiContracts\Response\BalanceResponseInterface;
use Tourze\OpenAiContracts\Response\ChatCompletionResponseInterface;
use Tourze\OpenAiContracts\Response\ModelListResponseInterface;

abstract class AbstractOpenAiClient implements OpenAiCompatibleClientInterface
{
    protected ?string $apiKey = null;

    protected ?string $lastError = null;

    protected ?AuthenticationStrategyInterface $authenticationStrategy = null;

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    abstract public function getName(): string;

    public function getAuthenticationStrategy(): ?AuthenticationStrategyInterface
    {
        return $this->authenticationStrategy;
    }

    public function setAuthenticationStrategy(AuthenticationStrategyInterface $strategy): void
    {
        $this->authenticationStrategy = $strategy;
    }

    public function chatCompletion(ChatCompletionRequestInterface $request): ChatCompletionResponseInterface
    {
        try {
            $response = $this->doRequest(
                '/chat/completions',
                'POST',
                $request->toArray()
            );

            return $this->createChatCompletionResponse($response);
        } catch (\Exception $e) {
            $this->lastError = $e->getMessage();
            throw $e;
        }
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    abstract protected function doRequest(string $endpoint, string $method, array $data = []): array;

    /**
     * @param array<string, mixed> $data
     */
    abstract protected function createChatCompletionResponse(array $data): ChatCompletionResponseInterface;

    public function getBalance(): BalanceResponseInterface
    {
        try {
            $response = $this->doRequest('/dashboard/billing/credit_grants', 'GET');

            return $this->createBalanceResponse($response);
        } catch (\Exception $e) {
            $this->lastError = $e->getMessage();
            throw $e;
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    abstract protected function createBalanceResponse(array $data): BalanceResponseInterface;

    public function isAvailable(): bool
    {
        try {
            $this->listModels();

            return true;
        } catch (\Exception $e) {
            $this->lastError = $e->getMessage();

            return false;
        }
    }

    public function listModels(): ModelListResponseInterface
    {
        try {
            $response = $this->doRequest('/models', 'GET');

            return $this->createModelListResponse($response);
        } catch (\Exception $e) {
            $this->lastError = $e->getMessage();
            throw $e;
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    abstract protected function createModelListResponse(array $data): ModelListResponseInterface;

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /**
     * @param array<string, mixed> $response
     */
    abstract protected function parseError(array $response): string;

    /**
     * @param array<string, mixed> $headers
     * @param array<string, mixed> $options
     * @return array{headers: array<string, mixed>, options: array<string, mixed>}
     */
    protected function applyAuthentication(array $headers, array $options): array
    {
        if (null !== $this->authenticationStrategy) {
            return $this->authenticationStrategy->applyAuthentication($headers, $options);
        }

        if (null !== $this->apiKey) {
            $headers['Authorization'] = 'Bearer ' . $this->apiKey;
        }

        return [
            'headers' => $headers,
            'options' => $options,
        ];
    }
}
