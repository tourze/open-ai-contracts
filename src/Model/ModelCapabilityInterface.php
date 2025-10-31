<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Model;

interface ModelCapabilityInterface
{
    public function supportsChatCompletion(): bool;

    public function supportsCompletion(): bool;

    public function supportsEmbedding(): bool;

    public function supportsFunctionCalling(): bool;

    public function supportsVision(): bool;

    public function supportsJsonMode(): bool;

    public function getMaxTokens(): int;

    public function getInputPricePerMillion(): ?float;

    public function getOutputPricePerMillion(): ?float;

    /**
     * @return string[]
     */
    public function getSupportedLanguages(): array;
}
