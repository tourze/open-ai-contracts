<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Client;

use Tourze\OpenAiContracts\Request\ChatCompletionRequestInterface;
use Tourze\OpenAiContracts\Response\BalanceResponseInterface;
use Tourze\OpenAiContracts\Response\ChatCompletionResponseInterface;
use Tourze\OpenAiContracts\Response\ModelListResponseInterface;

interface OpenAiCompatibleClientInterface
{
    public function chatCompletion(ChatCompletionRequestInterface $request): ChatCompletionResponseInterface;

    public function listModels(): ModelListResponseInterface;

    public function getBalance(): BalanceResponseInterface;

    public function setApiKey(string $apiKey): void;

    public function getApiKey(): ?string;

    public function getName(): string;

    public function getBaseUrl(): string;

    public function isAvailable(): bool;

    public function getLastError(): ?string;
}
