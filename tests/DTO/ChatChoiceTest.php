<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Tests\DTO;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\OpenAiContracts\DTO\ChatChoice;
use Tourze\OpenAiContracts\DTO\ChatMessage;
use Tourze\OpenAiContracts\Enum\FinishReason;
use Tourze\OpenAiContracts\Enum\Role;

/**
 * @internal
 */
#[CoversClass(ChatChoice::class)]
class ChatChoiceTest extends TestCase
{
    public function testConstructWithMinimalParametersShouldCreateInstance(): void
    {
        $message = new ChatMessage(Role::ASSISTANT, 'Hello, world!');
        $choice = new ChatChoice(
            index: 0,
            message: $message
        );

        $this->assertSame(0, $choice->getIndex());
        $this->assertSame($message, $choice->getMessage());
        $this->assertNull($choice->getDelta());
        $this->assertNull($choice->getFinishReason());
        $this->assertNull($choice->getLogprobs());
    }

    public function testConstructWithAllParametersShouldCreateInstance(): void
    {
        $message = new ChatMessage(Role::ASSISTANT, 'Complete response');
        $delta = new ChatMessage(Role::ASSISTANT, 'Delta content');
        $logprobs = ['tokens' => ['hello'], 'probabilities' => [0.95]];

        $choice = new ChatChoice(
            index: 1,
            message: $message,
            delta: $delta,
            finishReason: FinishReason::STOP,
            logprobs: $logprobs
        );

        $this->assertSame(1, $choice->getIndex());
        $this->assertSame($message, $choice->getMessage());
        $this->assertSame($delta, $choice->getDelta());
        $this->assertSame(FinishReason::STOP, $choice->getFinishReason());
        $this->assertSame($logprobs, $choice->getLogprobs());
    }

    public function testConstructWithStringFinishReasonShouldCreateInstance(): void
    {
        $message = new ChatMessage(Role::ASSISTANT, 'Response');
        $choice = new ChatChoice(
            index: 0,
            message: $message,
            finishReason: 'custom_stop'
        );

        $this->assertSame('custom_stop', $choice->getFinishReason());
    }

    public function testFromArrayWithMinimalDataShouldCreateInstance(): void
    {
        $data = [
            'index' => 2,
            'message' => [
                'role' => 'assistant',
                'content' => 'Test message',
            ],
        ];

        $choice = ChatChoice::fromArray($data);

        $this->assertSame(2, $choice->getIndex());
        $this->assertInstanceOf(ChatMessage::class, $choice->getMessage());
        $this->assertSame('assistant', $choice->getMessage()->getRole());
        $this->assertSame('Test message', $choice->getMessage()->getContent());
        $this->assertNull($choice->getDelta());
        $this->assertNull($choice->getFinishReason());
        $this->assertNull($choice->getLogprobs());
    }

    public function testFromArrayWithCompleteDataShouldCreateInstance(): void
    {
        $data = [
            'index' => 3,
            'message' => [
                'role' => 'assistant',
                'content' => 'Full response',
                'name' => 'assistant_name',
            ],
            'delta' => [
                'role' => 'assistant',
                'content' => 'Delta response',
            ],
            'finish_reason' => 'stop',
            'logprobs' => [
                'tokens' => ['test', 'tokens'],
                'token_logprobs' => [-0.1, -0.2],
            ],
        ];

        $choice = ChatChoice::fromArray($data);

        $this->assertSame(3, $choice->getIndex());
        $this->assertInstanceOf(ChatMessage::class, $choice->getMessage());
        $this->assertSame('Full response', $choice->getMessage()->getContent());
        $this->assertInstanceOf(ChatMessage::class, $choice->getDelta());
        $this->assertSame('Delta response', $choice->getDelta()->getContent());
        $this->assertSame('stop', $choice->getFinishReason());
        $this->assertSame(['tokens' => ['test', 'tokens'], 'token_logprobs' => [-0.1, -0.2]], $choice->getLogprobs());
    }

    public function testFromArrayWithMissingValuesShouldUseDefaults(): void
    {
        $data = [];

        $choice = ChatChoice::fromArray($data);

        $this->assertSame(0, $choice->getIndex());
        $this->assertInstanceOf(ChatMessage::class, $choice->getMessage());
        $this->assertSame('assistant', $choice->getMessage()->getRole());
        $this->assertSame('', $choice->getMessage()->getContent());
        $this->assertNull($choice->getDelta());
        $this->assertNull($choice->getFinishReason());
        $this->assertNull($choice->getLogprobs());
    }

    public function testToArrayWithMinimalDataShouldReturnCorrectArray(): void
    {
        $message = new ChatMessage(Role::USER, 'User message');
        $choice = new ChatChoice(
            index: 0,
            message: $message
        );

        $expected = [
            'index' => 0,
            'message' => [
                'role' => 'user',
                'content' => 'User message',
            ],
        ];

        $this->assertSame($expected, $choice->toArray());
    }

    public function testToArrayWithCompleteDataShouldReturnCorrectArray(): void
    {
        $message = new ChatMessage(Role::ASSISTANT, 'Assistant response', 'bot');
        $delta = new ChatMessage(Role::ASSISTANT, 'Partial update');
        $logprobs = ['data' => 'logprob_data'];

        $choice = new ChatChoice(
            index: 1,
            message: $message,
            delta: $delta,
            finishReason: FinishReason::LENGTH,
            logprobs: $logprobs
        );

        $expected = [
            'index' => 1,
            'message' => [
                'role' => 'assistant',
                'content' => 'Assistant response',
                'name' => 'bot',
            ],
            'delta' => [
                'role' => 'assistant',
                'content' => 'Partial update',
            ],
            'finish_reason' => 'length',
            'logprobs' => ['data' => 'logprob_data'],
        ];

        $this->assertSame($expected, $choice->toArray());
    }

    public function testToArrayWithStringFinishReasonShouldReturnString(): void
    {
        $message = new ChatMessage(Role::ASSISTANT, 'Response');
        $choice = new ChatChoice(
            index: 0,
            message: $message,
            finishReason: 'custom_reason'
        );

        $result = $choice->toArray();

        $this->assertSame('custom_reason', $result['finish_reason']);
    }

    public function testSerializationRoundTripShouldPreserveData(): void
    {
        $originalData = [
            'index' => 5,
            'message' => [
                'role' => 'assistant',
                'content' => 'Serialization test',
                'name' => 'test_bot',
                'function_call' => ['name' => 'test_function', 'arguments' => '{}'],
            ],
            'delta' => [
                'role' => 'assistant',
                'content' => 'Delta test',
            ],
            'finish_reason' => 'function_call',
            'logprobs' => [
                'tokens' => ['serialize', 'test'],
                'probabilities' => [0.8, 0.9],
            ],
        ];

        $choice = ChatChoice::fromArray($originalData);
        $serializedData = $choice->toArray();
        $reconstructedChoice = ChatChoice::fromArray($serializedData);

        $this->assertSame($choice->getIndex(), $reconstructedChoice->getIndex());
        $this->assertEquals($choice->getMessage()->toArray(), $reconstructedChoice->getMessage()->toArray());
        $this->assertEquals($choice->getDelta()?->toArray(), $reconstructedChoice->getDelta()?->toArray());
        $this->assertSame($choice->getFinishReason(), $reconstructedChoice->getFinishReason());
        $this->assertSame($choice->getLogprobs(), $reconstructedChoice->getLogprobs());
    }

    public function testAllFinishReasonEnumValuesShouldBeHandledCorrectly(): void
    {
        $message = new ChatMessage(Role::ASSISTANT, 'Test');

        foreach (FinishReason::cases() as $finishReason) {
            $choice = new ChatChoice(
                index: 0,
                message: $message,
                finishReason: $finishReason
            );

            $this->assertSame($finishReason, $choice->getFinishReason());
            $this->assertSame($finishReason->value, $choice->toArray()['finish_reason']);
        }
    }

    public function testGetterMethodsShouldReturnCorrectValues(): void
    {
        $message = new ChatMessage(Role::FUNCTION, 'Function result');
        $delta = new ChatMessage(Role::TOOL, 'Tool delta');
        $logprobs = ['custom' => 'logprob_structure'];

        $choice = new ChatChoice(
            index: 99,
            message: $message,
            delta: $delta,
            finishReason: FinishReason::TOOL_CALLS,
            logprobs: $logprobs
        );

        $this->assertSame(99, $choice->getIndex());
        $this->assertSame($message, $choice->getMessage());
        $this->assertSame($delta, $choice->getDelta());
        $this->assertSame(FinishReason::TOOL_CALLS, $choice->getFinishReason());
        $this->assertSame($logprobs, $choice->getLogprobs());
    }
}
