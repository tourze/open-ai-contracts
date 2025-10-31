<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Request;

use Tourze\OpenAiContracts\DTO\ChatMessage;

interface ChatCompletionRequestInterface extends OpenAiRequestInterface
{
    /**
     * @param ChatMessage[] $messages
     */
    public function setMessages(array $messages): void;

    /**
     * @return ChatMessage[]
     */
    public function getMessages(): array;

    public function setModel(string $model): void;

    public function getModel(): string;

    public function setTemperature(?float $temperature): void;

    public function getTemperature(): ?float;

    public function setMaxTokens(?int $maxTokens): void;

    public function getMaxTokens(): ?int;

    public function setTopP(?float $topP): void;

    public function getTopP(): ?float;

    public function setN(?int $n): void;

    public function getN(): ?int;

    public function setStream(?bool $stream): void;

    public function getStream(): ?bool;

    /**
     * @param string|string[]|null $stop
     */
    public function setStop(string|array|null $stop): void;

    /**
     * @return string|string[]|null
     */
    public function getStop(): string|array|null;

    public function setPresencePenalty(?float $presencePenalty): void;

    public function getPresencePenalty(): ?float;

    public function setFrequencyPenalty(?float $frequencyPenalty): void;

    public function getFrequencyPenalty(): ?float;

    public function setUser(?string $user): void;

    public function getUser(): ?string;
}
