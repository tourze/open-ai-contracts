<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Authentication;

abstract class HmacSignatureAuthenticationStrategy implements AuthenticationStrategyInterface
{
    /**
     * @param array<string, mixed> $headers
     * @param array<string, mixed> $options
     * @return array{headers: array<string, mixed>, options: array<string, mixed>}
     */
    public function applyAuthentication(array $headers, array $options): array
    {
        $now = gmdate('Ymd\THis\Z');
        $headers['X-Date'] = $now;

        $bodyValue = $options['body'] ?? '';
        $body = is_string($bodyValue) || is_numeric($bodyValue) ? (string) $bodyValue : '';
        if ('' !== $body) {
            $headers['X-Content-Sha256'] = hash('sha256', $body);
        }

        $methodValue = $options['method'] ?? 'POST';
        $method = is_string($methodValue) ? $methodValue : 'POST';

        $uriValue = $options['uri'] ?? '/';
        $uri = is_string($uriValue) ? $uriValue : '/';

        $signature = $this->generateSignature(
            $method,
            $uri,
            $headers,
            $body
        );

        $headers['Authorization'] = $signature;

        return [
            'headers' => $headers,
            'options' => $options,
        ];
    }

    /**
     * @param array<string, mixed> $headers
     */
    protected function generateSignature(string $method, string $uri, array $headers, string $body): string
    {
        $canonicalRequest = $this->buildCanonicalRequest($method, $uri, $headers, $body);
        $dateHeaderValue = $headers['X-Date'] ?? '';
        $dateHeader = is_string($dateHeaderValue) ? $dateHeaderValue : '';
        $credentialScope = $this->buildCredentialScope($dateHeader);
        $stringToSign = $this->buildStringToSign($canonicalRequest, $credentialScope, $dateHeader);

        $signingKey = $this->deriveSigningKey($dateHeader);
        $signature = hash_hmac('sha256', $stringToSign, $signingKey);

        return sprintf(
            'HMAC-SHA256 Credential=%s/%s, SignedHeaders=%s, Signature=%s',
            $this->getApiKey(),
            $credentialScope,
            $this->getSignedHeaders($headers),
            $signature
        );
    }

    /**
     * @param array<string, mixed> $headers
     */
    protected function buildCanonicalRequest(string $method, string $uri, array $headers, string $body): string
    {
        $canonicalHeaders = $this->buildCanonicalHeaders($headers);
        $signedHeaders = $this->getSignedHeaders($headers);
        $hashedPayload = hash('sha256', $body);

        return implode("\n", [
            strtoupper($method),
            $uri,
            '',
            $canonicalHeaders,
            '',
            $signedHeaders,
            $hashedPayload,
        ]);
    }

    /**
     * @param array<string, mixed> $headers
     */
    protected function buildCanonicalHeaders(array $headers): string
    {
        $canonical = [];
        foreach ($headers as $key => $value) {
            $valueString = is_string($value) || is_numeric($value) ? (string) $value : '';
            $canonical[strtolower($key)] = trim($valueString);
        }
        ksort($canonical);

        $result = [];
        foreach ($canonical as $key => $value) {
            $result[] = $key . ':' . $value;
        }

        return implode("\n", $result);
    }

    /**
     * @param array<string, mixed> $headers
     */
    protected function getSignedHeaders(array $headers): string
    {
        $keys = array_map('strtolower', array_keys($headers));
        sort($keys);

        return implode(';', $keys);
    }

    protected function buildCredentialScope(string $date): string
    {
        $dateOnly = substr($date, 0, 8);

        return sprintf('%s/%s/%s/request', $dateOnly, $this->getRegion(), $this->getService());
    }

    abstract protected function getRegion(): string;

    abstract protected function getService(): string;

    protected function buildStringToSign(string $canonicalRequest, string $credentialScope, string $date): string
    {
        return implode("\n", [
            'HMAC-SHA256',
            $date,
            $credentialScope,
            hash('sha256', $canonicalRequest),
        ]);
    }

    protected function deriveSigningKey(string $date): string
    {
        $dateOnly = substr($date, 0, 8);
        $kDate = hash_hmac('sha256', $dateOnly, 'AWS4' . $this->getSecretKey(), true);
        $kRegion = hash_hmac('sha256', $this->getRegion(), $kDate, true);
        $kService = hash_hmac('sha256', $this->getService(), $kRegion, true);

        return hash_hmac('sha256', 'request', $kService, true);
    }

    abstract protected function getSecretKey(): string;

    abstract protected function getApiKey(): string;

    public function handleAuthenticationError(int $statusCode, string $response): void
    {
        if ($this->isAuthenticationError($statusCode)) {
            $this->onAuthenticationError();
        }
    }

    public function isAuthenticationError(int $statusCode): bool
    {
        return 401 === $statusCode || 403 === $statusCode;
    }

    abstract protected function onAuthenticationError(): void;

    public function getAuthenticationErrorMessage(string $response): string
    {
        $data = json_decode($response, true);

        if (!is_array($data)) {
            return 'Authentication failed';
        }

        // Ensure all keys are strings for proper type safety
        $stringKeyedData = [];
        foreach ($data as $key => $value) {
            if (is_string($key)) {
                $stringKeyedData[$key] = $value;
            }
        }

        // AWS-style error message
        $awsMessage = $this->extractAwsErrorMessage($stringKeyedData);
        if (null !== $awsMessage) {
            return $awsMessage;
        }

        // OpenAI-style error message
        $openAiMessage = $this->extractOpenAiErrorMessage($stringKeyedData);
        if (null !== $openAiMessage) {
            return $openAiMessage;
        }

        // Generic message field
        if (isset($stringKeyedData['message']) && is_string($stringKeyedData['message'])) {
            return $stringKeyedData['message'];
        }

        return 'Authentication failed';
    }

    /**
     * @param array<string, mixed> $data
     */
    private function extractAwsErrorMessage(array $data): ?string
    {
        if (!isset($data['ResponseMetadata']) || !is_array($data['ResponseMetadata'])) {
            return null;
        }

        if (!isset($data['ResponseMetadata']['Error']) || !is_array($data['ResponseMetadata']['Error'])) {
            return null;
        }

        $message = $data['ResponseMetadata']['Error']['Message'] ?? null;

        return is_string($message) ? $message : null;
    }

    /**
     * @param array<string, mixed> $data
     */
    private function extractOpenAiErrorMessage(array $data): ?string
    {
        if (!isset($data['error']) || !is_array($data['error'])) {
            return null;
        }

        $message = $data['error']['message'] ?? null;

        return is_string($message) ? $message : null;
    }
}
