<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\DTO;

use Tourze\OpenAiContracts\Enum\FinishReason;

class ChatChoice
{
    /**
     * @param array<string, mixed>|null $logprobs
     */
    public function __construct(
        private readonly int $index,
        private readonly ChatMessage $message,
        private readonly ?ChatMessage $delta = null,
        private readonly FinishReason|string|null $finishReason = null,
        private readonly ?array $logprobs = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        // Type-safe extraction with proper conversions
        $index = 0;
        if (isset($data['index']) && (is_int($data['index']) || is_string($data['index']))) {
            $index = (int) $data['index'];
        }

        $message = new ChatMessage('assistant', '');
        if (isset($data['message']) && is_array($data['message'])) {
            /** @var array<string, mixed> $messageData */
            $messageData = $data['message'];
            $message = ChatMessage::fromArray($messageData);
        }

        $delta = null;
        if (isset($data['delta']) && is_array($data['delta'])) {
            /** @var array<string, mixed> $deltaData */
            $deltaData = $data['delta'];
            $delta = ChatMessage::fromArray($deltaData);
        }

        /** @var FinishReason|string|null $finishReason */
        $finishReason = null;
        if (isset($data['finish_reason']) && (is_string($data['finish_reason']) || $data['finish_reason'] instanceof FinishReason)) {
            $finishReason = $data['finish_reason'];
        }

        /** @var array<string, mixed>|null $logprobs */
        $logprobs = null;
        if (isset($data['logprobs']) && is_array($data['logprobs'])) {
            $logprobs = self::ensureStringKeysArray($data['logprobs']);
        }

        return new self(
            index: $index,
            message: $message,
            delta: $delta,
            finishReason: $finishReason,
            logprobs: $logprobs,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'index' => $this->index,
            'message' => $this->message->toArray(),
        ];

        if (null !== $this->delta) {
            $data['delta'] = $this->delta->toArray();
        }

        if (null !== $this->finishReason) {
            $data['finish_reason'] = $this->finishReason instanceof FinishReason ? $this->finishReason->value : $this->finishReason;
        }

        if (null !== $this->logprobs) {
            $data['logprobs'] = $this->logprobs;
        }

        return $data;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function getMessage(): ChatMessage
    {
        return $this->message;
    }

    public function getDelta(): ?ChatMessage
    {
        return $this->delta;
    }

    public function getFinishReason(): FinishReason|string|null
    {
        return $this->finishReason;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getLogprobs(): ?array
    {
        return $this->logprobs;
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
