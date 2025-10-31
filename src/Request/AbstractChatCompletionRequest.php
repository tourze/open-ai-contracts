<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Request;

use Tourze\OpenAiContracts\DTO\ChatMessage;
use Tourze\OpenAiContracts\Exception\InvalidRequestException;

abstract class AbstractChatCompletionRequest extends AbstractOpenAiRequest implements ChatCompletionRequestInterface
{
    /**
     * @var ChatMessage[]
     */
    protected array $messages = [];

    protected string $model = 'gpt-3.5-turbo';

    protected ?float $temperature = null;

    protected ?int $maxTokens = null;

    protected ?float $topP = null;

    protected ?int $n = null;

    protected ?bool $stream = null;

    /**
     * @var string|string[]|null
     */
    protected string|array|null $stop = null;

    protected ?float $presencePenalty = null;

    protected ?float $frequencyPenalty = null;

    protected ?string $user = null;

    public function getEndpoint(): string
    {
        return '/chat/completions';
    }

    public function getMethod(): string
    {
        return 'POST';
    }

    /**
     * @return ChatMessage[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param ChatMessage[] $messages
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(?float $temperature): void
    {
        $this->temperature = $temperature;
    }

    public function getMaxTokens(): ?int
    {
        return $this->maxTokens;
    }

    public function setMaxTokens(?int $maxTokens): void
    {
        $this->maxTokens = $maxTokens;
    }

    public function getTopP(): ?float
    {
        return $this->topP;
    }

    public function setTopP(?float $topP): void
    {
        $this->topP = $topP;
    }

    public function getN(): ?int
    {
        return $this->n;
    }

    public function setN(?int $n): void
    {
        $this->n = $n;
    }

    public function getStream(): ?bool
    {
        return $this->stream;
    }

    public function setStream(?bool $stream): void
    {
        $this->stream = $stream;
    }

    /**
     * @return string|string[]|null
     */
    public function getStop(): string|array|null
    {
        return $this->stop;
    }

    /**
     * @param string|string[]|null $stop
     */
    public function setStop(string|array|null $stop): void
    {
        $this->stop = $stop;
    }

    public function getPresencePenalty(): ?float
    {
        return $this->presencePenalty;
    }

    public function setPresencePenalty(?float $presencePenalty): void
    {
        $this->presencePenalty = $presencePenalty;
    }

    public function getFrequencyPenalty(): ?float
    {
        return $this->frequencyPenalty;
    }

    public function setFrequencyPenalty(?float $frequencyPenalty): void
    {
        $this->frequencyPenalty = $frequencyPenalty;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(?string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'model' => $this->model,
            'messages' => array_map(fn (ChatMessage $message) => $message->toArray(), $this->messages),
        ];

        if (null !== $this->temperature) {
            $data['temperature'] = $this->temperature;
        }

        if (null !== $this->maxTokens) {
            $data['max_tokens'] = $this->maxTokens;
        }

        if (null !== $this->topP) {
            $data['top_p'] = $this->topP;
        }

        if (null !== $this->n) {
            $data['n'] = $this->n;
        }

        if (null !== $this->stream) {
            $data['stream'] = $this->stream;
        }

        if (null !== $this->stop) {
            $data['stop'] = $this->stop;
        }

        if (null !== $this->presencePenalty) {
            $data['presence_penalty'] = $this->presencePenalty;
        }

        if (null !== $this->frequencyPenalty) {
            $data['frequency_penalty'] = $this->frequencyPenalty;
        }

        if (null !== $this->user) {
            $data['user'] = $this->user;
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function validateData(array $data): void
    {
        if (!isset($data['messages']) || !is_array($data['messages']) || [] === $data['messages']) {
            throw new InvalidRequestException('Messages cannot be empty', 400, null, 'invalid_request', 'validation_error', 'messages');
        }

        if (!isset($data['model']) || !is_string($data['model']) || '' === $data['model']) {
            throw new InvalidRequestException('Model cannot be empty', 400, null, 'invalid_request', 'validation_error', 'model');
        }
    }
}
