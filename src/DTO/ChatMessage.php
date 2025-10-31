<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\DTO;

use Tourze\OpenAiContracts\Enum\Role;

class ChatMessage
{
    /**
     * @param array<string, mixed>|null $functionCall
     * @param array<string, mixed>|null $toolCalls
     */
    public function __construct(
        private readonly Role|string $role,
        private readonly string $content,
        private readonly ?string $name = null,
        private readonly ?array $functionCall = null,
        private readonly ?array $toolCalls = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        // Type-safe extraction with proper conversions
        /** @var Role|string $role */
        $role = 'user';
        if (isset($data['role']) && (is_string($data['role']) || $data['role'] instanceof Role)) {
            $role = $data['role'];
        }

        $content = '';
        if (isset($data['content']) && is_string($data['content'])) {
            $content = $data['content'];
        }

        $name = null;
        if (isset($data['name']) && is_string($data['name'])) {
            $name = $data['name'];
        }

        /** @var array<string, mixed>|null $functionCall */
        $functionCall = null;
        if (isset($data['function_call']) && is_array($data['function_call'])) {
            $functionCall = self::ensureStringKeysArray($data['function_call']);
        }

        /** @var array<string, mixed>|null $toolCalls */
        $toolCalls = null;
        if (isset($data['tool_calls']) && is_array($data['tool_calls'])) {
            $toolCalls = self::ensureStringKeysArray($data['tool_calls']);
        }

        return new self(
            role: $role,
            content: $content,
            name: $name,
            functionCall: $functionCall,
            toolCalls: $toolCalls,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'role' => $this->role instanceof Role ? $this->role->value : $this->role,
            'content' => $this->content,
        ];

        if (null !== $this->name) {
            $data['name'] = $this->name;
        }

        if (null !== $this->functionCall) {
            $data['function_call'] = $this->functionCall;
        }

        if (null !== $this->toolCalls) {
            $data['tool_calls'] = $this->toolCalls;
        }

        return $data;
    }

    public function getRole(): Role|string
    {
        return $this->role;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getFunctionCall(): ?array
    {
        return $this->functionCall;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getToolCalls(): ?array
    {
        return $this->toolCalls;
    }

    /**
     * 确保数组键都是字符串类型以满足PHPStan兼容性。
     *
     * @param array<mixed> $array
     * @return array<string, mixed>
     */
    private static function ensureStringKeysArray(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[(string) $key] = $value;
        }

        return $result;
    }
}
