# OpenAI Contracts

[English](README.md) | [中文](README.zh-CN.md)

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![PHP Version](https://img.shields.io/badge/php-^8.2-blue.svg)
![Tests](https://img.shields.io/badge/tests-passing-green.svg)

OpenAI compatible API contracts and interfaces for PHP applications.

## Installation

```bash
composer require tourze/open-ai-contracts
```

## Features

This package provides a comprehensive set of contracts and interfaces for building OpenAI-compatible API clients:

### Core Components

- **Client Interfaces**: Define standard methods for OpenAI API interactions
- **Request/Response Contracts**: Type-safe request and response structures  
- **DTO Classes**: Data transfer objects for API communication
- **Enums**: Standardized enumerations for roles, model types, and finish reasons
- **Attributes**: Annotations for API compatibility and authentication
- **Exception Interfaces**: Structured error handling contracts

### Client Interface

```php
use Tourze\OpenAiContracts\Client\OpenAiCompatibleClientInterface;

interface OpenAiCompatibleClientInterface
{
    public function chatCompletion(ChatCompletionRequestInterface $request): ChatCompletionResponseInterface;
    public function listModels(): ModelListResponseInterface;
    public function getBalance(): BalanceResponseInterface;
    public function setApiKey(string $apiKey): void;
    public function getApiKey(): ?string;
    public function getName(): string;
    public function getBaseUrl(): string;
    public function isAvailable(): bool;
    public function getLastError(): ?string;
}
```

### Data Transfer Objects

#### Chat Message

```php
use Tourze\OpenAiContracts\DTO\ChatMessage;
use Tourze\OpenAiContracts\Enum\Role;

$message = new ChatMessage(
    role: Role::USER,
    content: 'Hello, how are you?'
);

// Convert to array for API requests
$messageArray = $message->toArray();

// Create from API response
$message = ChatMessage::fromArray($responseData);
```

#### Usage Statistics

```php
use Tourze\OpenAiContracts\DTO\Usage;

$usage = new Usage(
    promptTokens: 100,
    completionTokens: 50,
    totalTokens: 150
);
```

### Enumerations

#### Role Enum

```php
use Tourze\OpenAiContracts\Enum\Role;

Role::USER;       // 'user'
Role::ASSISTANT;  // 'assistant'
Role::SYSTEM;     // 'system'
Role::FUNCTION;   // 'function'
Role::TOOL;       // 'tool'
```

#### Model Type

```php
use Tourze\OpenAiContracts\Enum\ModelType;

ModelType::CHAT_COMPLETION;  // 'chat.completion'
ModelType::TEXT_COMPLETION;  // 'text.completion'
ModelType::EMBEDDING;        // 'embedding'
// ... and more
```

### Attributes

Mark classes and methods as OpenAI compatible:

```php
use Tourze\OpenAiContracts\Attribute\OpenAiCompatible;
use Tourze\OpenAiContracts\Attribute\OpenAiEndpoint;
use Tourze\OpenAiContracts\Attribute\RequiresAuthentication;
use Tourze\OpenAiContracts\Attribute\RateLimited;

#[OpenAiCompatible(version: 'v1')]
#[RequiresAuthentication]
class MyApiClient
{
    #[OpenAiEndpoint('/v1/chat/completions')]
    #[RateLimited(requests: 100, timeWindow: 60)]
    public function chatCompletion(): void
    {
        // Implementation
    }
}
```

### Exception Handling

```php
use Tourze\OpenAiContracts\Exception\InvalidRequestException;
use Tourze\OpenAiContracts\Exception\OpenAiExceptionInterface;

try {
    // API call
} catch (InvalidRequestException $e) {
    // Handle invalid request
} catch (OpenAiExceptionInterface $e) {
    // Handle general OpenAI errors
}
```

## Requirements

- PHP 8.2 or higher
- tourze/enum-extra ^1.0

## Quality

This package follows strict quality standards:

- **Static Analysis**: Uses PHPStan for comprehensive static analysis
- **Testing**: Full test coverage with PHPUnit
- **Code Style**: Follows PSR-12 coding standards
- **Type Safety**: Full type declarations and strict typing

## Architecture

### Request/Response Flow

```
Client Request → DTO Validation → Interface Contract → API Response → DTO Response
```

### Package Structure

```
src/
├── Attribute/          # PHP Attributes for API compatibility
├── Client/            # Client interfaces
├── DTO/               # Data Transfer Objects
├── Enum/              # Enumerations for constants
├── Exception/         # Exception interfaces
├── Request/           # Request interfaces
└── Response/          # Response interfaces
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run tests and static analysis
5. Commit your changes (`git commit -m 'Add some amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

## Development Setup

```bash
# Clone the repository
git clone https://github.com/tourze/php-monorepo.git
cd php-monorepo/packages/open-ai-contracts

# Install dependencies
composer install

# Run tests
composer test

# Run static analysis
composer analyse
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.