<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Request;

abstract class AbstractOpenAiRequest implements OpenAiRequestInterface
{
    public function requiresAuthentication(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    public function validate(): void
    {
        $data = $this->toArray();
        $this->validateData($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function validateData(array $data): void
    {
    }
}
