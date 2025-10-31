<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Model;

interface ModelInfoInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getType(): string;

    public function getContextLength(): int;

    public function getOwnedBy(): string;

    public function getCreated(): ?int;

    public function isActive(): bool;

    /**
     * @return array<string, mixed>
     */
    public function getPermissions(): array;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
