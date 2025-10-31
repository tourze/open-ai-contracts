<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Exception;

interface RateLimitExceptionInterface extends OpenAiExceptionInterface
{
    public function getRetryAfter(): ?int;

    public function getRateLimitLimit(): ?int;

    public function getRateLimitRemaining(): ?int;

    public function getRateLimitReset(): ?\DateTimeInterface;
}
