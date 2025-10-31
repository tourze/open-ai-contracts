<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\DTO;

class Usage
{
    /**
     * @param array<string, mixed>|null $promptTokensDetails
     * @param array<string, mixed>|null $completionTokensDetails
     */
    public function __construct(
        private readonly int $promptTokens,
        private readonly int $completionTokens,
        private readonly int $totalTokens,
        private readonly ?array $promptTokensDetails = null,
        private readonly ?array $completionTokensDetails = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        // Type-safe extraction with proper conversions
        $promptTokens = 0;
        if (isset($data['prompt_tokens']) && (is_int($data['prompt_tokens']) || is_string($data['prompt_tokens']))) {
            $promptTokens = (int) $data['prompt_tokens'];
        }

        $completionTokens = 0;
        if (isset($data['completion_tokens']) && (is_int($data['completion_tokens']) || is_string($data['completion_tokens']))) {
            $completionTokens = (int) $data['completion_tokens'];
        }

        $totalTokens = 0;
        if (isset($data['total_tokens']) && (is_int($data['total_tokens']) || is_string($data['total_tokens']))) {
            $totalTokens = (int) $data['total_tokens'];
        }

        /** @var array<string, mixed>|null $promptTokensDetails */
        $promptTokensDetails = null;
        if (isset($data['prompt_tokens_details']) && is_array($data['prompt_tokens_details'])) {
            $promptTokensDetails = self::ensureStringKeysArray($data['prompt_tokens_details']);
        }

        /** @var array<string, mixed>|null $completionTokensDetails */
        $completionTokensDetails = null;
        if (isset($data['completion_tokens_details']) && is_array($data['completion_tokens_details'])) {
            $completionTokensDetails = self::ensureStringKeysArray($data['completion_tokens_details']);
        }

        return new self(
            promptTokens: $promptTokens,
            completionTokens: $completionTokens,
            totalTokens: $totalTokens,
            promptTokensDetails: $promptTokensDetails,
            completionTokensDetails: $completionTokensDetails,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'prompt_tokens' => $this->promptTokens,
            'completion_tokens' => $this->completionTokens,
            'total_tokens' => $this->totalTokens,
        ];

        if (null !== $this->promptTokensDetails) {
            $data['prompt_tokens_details'] = $this->promptTokensDetails;
        }

        if (null !== $this->completionTokensDetails) {
            $data['completion_tokens_details'] = $this->completionTokensDetails;
        }

        return $data;
    }

    public function getPromptTokens(): int
    {
        return $this->promptTokens;
    }

    public function getCompletionTokens(): int
    {
        return $this->completionTokens;
    }

    public function getTotalTokens(): int
    {
        return $this->totalTokens;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getPromptTokensDetails(): ?array
    {
        return $this->promptTokensDetails;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getCompletionTokensDetails(): ?array
    {
        return $this->completionTokensDetails;
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
