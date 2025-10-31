<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Exception;

interface OpenAiExceptionInterface extends \Throwable
{
    public function getErrorCode(): ?string;

    public function getErrorType(): ?string;

    public function getParam(): ?string;

    public function getRequestId(): ?string;

    public function getStatusCode(): ?int;
}
