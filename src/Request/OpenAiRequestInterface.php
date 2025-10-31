<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Request;

interface OpenAiRequestInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;

    public function getEndpoint(): string;

    public function getMethod(): string;

    public function requiresAuthentication(): bool;

    /**
     * @return array<string, mixed>
     */
    public function getHeaders(): array;

    public function validate(): void;
}
