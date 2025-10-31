<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\OpenAiContracts\Exception\InvalidRequestException;
use Tourze\OpenAiContracts\Exception\OpenAiExceptionInterface;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;

/**
 * @internal
 */
#[CoversClass(InvalidRequestException::class)]
class InvalidRequestExceptionTest extends AbstractExceptionTestCase
{
    public function testConstructorWithDefaults(): void
    {
        $exception = new InvalidRequestException();

        $this->assertSame('', $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
        $this->assertNull($exception->getErrorCode());
        $this->assertNull($exception->getErrorType());
        $this->assertNull($exception->getParam());
        $this->assertNull($exception->getRequestId());
        $this->assertNull($exception->getStatusCode());
    }

    public function testConstructorWithBasicParameters(): void
    {
        $message = 'Invalid API request';
        $code = 400;

        $exception = new InvalidRequestException($message, $code);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }

    public function testConstructorWithAllParameters(): void
    {
        $message = 'Invalid API request';
        $code = 400;
        $previous = new \RuntimeException('Previous exception');
        $errorCode = 'invalid_request_error';
        $errorType = 'invalid_request';
        $param = 'model';
        $requestId = 'req_123456';
        $statusCode = 400;

        $exception = new InvalidRequestException(
            $message,
            $code,
            $previous,
            $errorCode,
            $errorType,
            $param,
            $requestId,
            $statusCode,
        );

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
        $this->assertSame($errorCode, $exception->getErrorCode());
        $this->assertSame($errorType, $exception->getErrorType());
        $this->assertSame($param, $exception->getParam());
        $this->assertSame($requestId, $exception->getRequestId());
        $this->assertSame($statusCode, $exception->getStatusCode());
    }

    public function testConstructorWithPartialParameters(): void
    {
        $exception = new InvalidRequestException(
            message: 'API quota exceeded',
            errorCode: 'quota_exceeded',
            statusCode: 429,
        );

        $this->assertSame('API quota exceeded', $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame('quota_exceeded', $exception->getErrorCode());
        $this->assertSame(429, $exception->getStatusCode());
        $this->assertNull($exception->getErrorType());
        $this->assertNull($exception->getParam());
        $this->assertNull($exception->getRequestId());
    }

    public function testImplementsOpenAiExceptionInterface(): void
    {
        $exception = new InvalidRequestException();

        $this->assertInstanceOf(OpenAiExceptionInterface::class, $exception);
    }

    public function testExtendsRuntimeException(): void
    {
        $exception = new InvalidRequestException();

        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }

    public function testCanBeThrown(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('Test exception');
        $this->expectExceptionCode(123);

        throw new InvalidRequestException('Test exception', 123);
    }

    public function testChainedException(): void
    {
        $previous = new \InvalidArgumentException('Original error');
        $exception = new InvalidRequestException(
            'Wrapped error',
            500,
            $previous,
        );

        $this->assertSame($previous, $exception->getPrevious());
        $this->assertSame('Original error', $exception->getPrevious()->getMessage());
    }

    public function testRealWorldScenario(): void
    {
        $exception = new InvalidRequestException(
            message: 'The model parameter is required but was not provided',
            code: 0,
            previous: null,
            errorCode: 'missing_required_parameter',
            errorType: 'invalid_request_error',
            param: 'model',
            requestId: 'req_7f3a2b1c8d9e0f1a2b3c4d5e6f7a8b9c',
            statusCode: 400,
        );

        $this->assertSame('The model parameter is required but was not provided', $exception->getMessage());
        $this->assertSame('missing_required_parameter', $exception->getErrorCode());
        $this->assertSame('invalid_request_error', $exception->getErrorType());
        $this->assertSame('model', $exception->getParam());
        $this->assertSame('req_7f3a2b1c8d9e0f1a2b3c4d5e6f7a8b9c', $exception->getRequestId());
        $this->assertSame(400, $exception->getStatusCode());
    }
}
