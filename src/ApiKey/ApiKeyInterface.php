<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\ApiKey;

interface ApiKeyInterface
{
    public function getKey(): string;

    public function getSecretKey(): ?string;

    public function getName(): ?string;

    public function isValid(): bool;

    public function isActive(): bool;

    public function setActive(bool $active): void;

    public function getLastUsedAt(): ?\DateTimeInterface;

    public function updateLastUsedAt(): void;

    public function getFailureCount(): int;

    public function incrementFailureCount(): void;

    public function resetFailureCount(): void;

    /**
     * @return array<string, mixed>
     */
    public function getMetadata(): array;

    /**
     * @param array<string, mixed> $metadata
     */
    public function setMetadata(array $metadata): void;
}
