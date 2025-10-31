<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\ApiKey;

interface ApiKeyManagerInterface
{
    public function getCurrentKey(): ApiKeyInterface;

    public function rotateKey(): ApiKeyInterface;

    public function markKeyAsInvalid(ApiKeyInterface $key): void;

    public function markKeyAsValid(ApiKeyInterface $key): void;

    /**
     * @return ApiKeyInterface[]
     */
    public function getAllKeys(): array;

    /**
     * @return ApiKeyInterface[]
     */
    public function getValidKeys(): array;

    public function addKey(ApiKeyInterface $key): void;

    public function removeKey(ApiKeyInterface $key): void;

    public function hasValidKeys(): bool;

    public function getKeyByValue(string $keyValue): ?ApiKeyInterface;
}
