<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Response;

use Tourze\OpenAiContracts\DTO\ChatChoice;
use Tourze\OpenAiContracts\DTO\Usage;

interface ChatCompletionResponseInterface extends OpenAiResponseInterface
{
    /**
     * @return ChatChoice[]
     */
    public function getChoices(): array;

    public function getUsage(): ?Usage;

    public function getModel(): ?string;

    public function getSystemFingerprint(): ?string;
}
