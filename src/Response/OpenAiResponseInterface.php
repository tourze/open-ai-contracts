<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Response;

interface OpenAiResponseInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): static;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;

    public function getId(): ?string;

    public function getObject(): ?string;

    public function getCreated(): ?int;
}
