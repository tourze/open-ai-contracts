<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Exception;

class InvalidRequestException extends \RuntimeException implements OpenAiExceptionInterface
{
    private ?string $errorCode = null;

    private ?string $errorType = null;

    private ?string $param = null;

    private ?string $requestId = null;

    private ?int $statusCode = null;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
        ?string $errorCode = null,
        ?string $errorType = null,
        ?string $param = null,
        ?string $requestId = null,
        ?int $statusCode = null,
    ) {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode;
        $this->errorType = $errorType;
        $this->param = $param;
        $this->requestId = $requestId;
        $this->statusCode = $statusCode;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getErrorType(): ?string
    {
        return $this->errorType;
    }

    public function getParam(): ?string
    {
        return $this->param;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }
}
