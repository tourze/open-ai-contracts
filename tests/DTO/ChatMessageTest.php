<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Tests\DTO;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\OpenAiContracts\DTO\ChatMessage;
use Tourze\OpenAiContracts\Enum\Role;

/**
 * @internal
 */
#[CoversClass(ChatMessage::class)]
class ChatMessageTest extends TestCase
{
    public function testConstructWithMinimalParametersShouldCreateInstance(): void
    {
        $message = new ChatMessage(
            role: Role::USER,
            content: 'Hello, AI!'
        );

        $this->assertSame(Role::USER, $message->getRole());
        $this->assertSame('Hello, AI!', $message->getContent());
        $this->assertNull($message->getName());
        $this->assertNull($message->getFunctionCall());
        $this->assertNull($message->getToolCalls());
    }

    public function testConstructWithAllParametersShouldCreateInstance(): void
    {
        $functionCall = ['name' => 'get_weather', 'arguments' => '{"location": "Tokyo"}'];
        $toolCalls = [['type' => 'function', 'function' => ['name' => 'search']]];

        $message = new ChatMessage(
            role: Role::ASSISTANT,
            content: 'Function call response',
            name: 'weather_bot',
            functionCall: $functionCall,
            toolCalls: $toolCalls // @phpstan-ignore-line
        );

        $this->assertSame(Role::ASSISTANT, $message->getRole());
        $this->assertSame('Function call response', $message->getContent());
        $this->assertSame('weather_bot', $message->getName());
        $this->assertSame($functionCall, $message->getFunctionCall());
        $this->assertSame($toolCalls, $message->getToolCalls());
    }

    public function testConstructWithStringRoleShouldCreateInstance(): void
    {
        $message = new ChatMessage(
            role: 'custom_role',
            content: 'Custom role message'
        );

        $this->assertSame('custom_role', $message->getRole());
        $this->assertSame('Custom role message', $message->getContent());
    }

    public function testFromArrayWithMinimalDataShouldCreateInstance(): void
    {
        $data = [
            'role' => 'system',
            'content' => 'System prompt',
        ];

        $message = ChatMessage::fromArray($data);

        $this->assertSame('system', $message->getRole());
        $this->assertSame('System prompt', $message->getContent());
        $this->assertNull($message->getName());
        $this->assertNull($message->getFunctionCall());
        $this->assertNull($message->getToolCalls());
    }

    public function testFromArrayWithCompleteDataShouldCreateInstance(): void
    {
        $data = [
            'role' => 'function',
            'content' => 'Function result',
            'name' => 'search_function',
            'function_call' => [
                'name' => 'search',
                'arguments' => '{"query": "test"}',
            ],
            'tool_calls' => [
                [
                    'id' => 'call_123',
                    'type' => 'function',
                    'function' => ['name' => 'calculator'],
                ],
            ],
        ];

        $message = ChatMessage::fromArray($data);

        $this->assertSame('function', $message->getRole());
        $this->assertSame('Function result', $message->getContent());
        $this->assertSame('search_function', $message->getName());
        $this->assertSame(['name' => 'search', 'arguments' => '{"query": "test"}'], $message->getFunctionCall());
        $this->assertSame([['id' => 'call_123', 'type' => 'function', 'function' => ['name' => 'calculator']]], $message->getToolCalls());
    }

    public function testFromArrayWithRoleEnumShouldCreateInstance(): void
    {
        $data = [
            'role' => Role::TOOL,
            'content' => 'Tool response',
        ];

        $message = ChatMessage::fromArray($data);

        $this->assertSame(Role::TOOL, $message->getRole());
        $this->assertSame('Tool response', $message->getContent());
    }

    public function testFromArrayWithMissingValuesShouldUseDefaults(): void
    {
        $data = [];

        $message = ChatMessage::fromArray($data);

        $this->assertSame('user', $message->getRole());
        $this->assertSame('', $message->getContent());
        $this->assertNull($message->getName());
        $this->assertNull($message->getFunctionCall());
        $this->assertNull($message->getToolCalls());
    }

    public function testToArrayWithMinimalDataShouldReturnCorrectArray(): void
    {
        $message = new ChatMessage(
            role: Role::USER,
            content: 'User question'
        );

        $expected = [
            'role' => 'user',
            'content' => 'User question',
        ];

        $this->assertSame($expected, $message->toArray());
    }

    public function testToArrayWithCompleteDataShouldReturnCorrectArray(): void
    {
        $functionCall = ['name' => 'calculate', 'arguments' => '{"x": 10, "y": 20}'];
        $toolCalls = [['id' => 'tool_1', 'type' => 'code_interpreter']];

        $message = new ChatMessage(
            role: Role::ASSISTANT,
            content: 'Calculation result',
            name: 'math_assistant',
            functionCall: $functionCall,
            toolCalls: $toolCalls // @phpstan-ignore-line
        );

        $expected = [
            'role' => 'assistant',
            'content' => 'Calculation result',
            'name' => 'math_assistant',
            'function_call' => ['name' => 'calculate', 'arguments' => '{"x": 10, "y": 20}'],
            'tool_calls' => [['id' => 'tool_1', 'type' => 'code_interpreter']],
        ];

        $this->assertSame($expected, $message->toArray());
    }

    public function testToArrayWithStringRoleShouldReturnString(): void
    {
        $message = new ChatMessage(
            role: 'custom',
            content: 'Custom content'
        );

        $result = $message->toArray();

        $this->assertSame('custom', $result['role']);
        $this->assertSame('Custom content', $result['content']);
    }

    public function testSerializationRoundTripShouldPreserveData(): void
    {
        $originalData = [
            'role' => 'assistant',
            'content' => 'Complete response with all fields',
            'name' => 'full_assistant',
            'function_call' => [
                'name' => 'complex_function',
                'arguments' => '{"param1": "value1", "param2": 42}',
            ],
            'tool_calls' => [
                [
                    'id' => 'call_abc123',
                    'type' => 'function',
                    'function' => [
                        'name' => 'data_processor',
                        'arguments' => '{"data": [1, 2, 3]}',
                    ],
                ],
                [
                    'id' => 'call_def456',
                    'type' => 'retrieval',
                    'retrieval' => ['query' => 'search term'],
                ],
            ],
        ];

        $message = ChatMessage::fromArray($originalData);
        $serializedData = $message->toArray();
        $reconstructedMessage = ChatMessage::fromArray($serializedData);

        $this->assertSame($message->getRole(), $reconstructedMessage->getRole());
        $this->assertSame($message->getContent(), $reconstructedMessage->getContent());
        $this->assertSame($message->getName(), $reconstructedMessage->getName());
        $this->assertSame($message->getFunctionCall(), $reconstructedMessage->getFunctionCall());
        $this->assertSame($message->getToolCalls(), $reconstructedMessage->getToolCalls());
    }

    public function testAllRoleEnumValuesShouldBeHandledCorrectly(): void
    {
        foreach (Role::cases() as $role) {
            $message = new ChatMessage(
                role: $role,
                content: "Message for role {$role->value}"
            );

            $this->assertSame($role, $message->getRole());
            $this->assertSame($role->value, $message->toArray()['role']);
        }
    }

    public function testEmptyContentShouldBeAllowed(): void
    {
        $message = new ChatMessage(
            role: Role::SYSTEM,
            content: ''
        );

        $this->assertSame('', $message->getContent());
        $this->assertSame('', $message->toArray()['content']);
    }

    public function testComplexToolCallsStructureShouldBePreserved(): void
    {
        $complexToolCalls = [
            [
                'id' => 'call_1',
                'type' => 'function',
                'function' => [
                    'name' => 'analyze_data',
                    'arguments' => json_encode([
                        'dataset' => 'sales_2024',
                        'metrics' => ['revenue', 'conversion'],
                        'filters' => ['region' => 'APAC', 'quarter' => 'Q1'],
                    ]),
                ],
            ],
            [
                'id' => 'call_2',
                'type' => 'code_interpreter',
                'code_interpreter' => [
                    'input' => 'import pandas as pd\ndf = pd.read_csv("data.csv")',
                ],
            ],
        ];

        $message = new ChatMessage(
            role: Role::ASSISTANT,
            content: 'I will analyze the data for you.',
            toolCalls: $complexToolCalls // @phpstan-ignore-line
        );

        $this->assertSame($complexToolCalls, $message->getToolCalls());

        // 验证序列化后的数据正确性
        $messageArray = $message->toArray();
        $this->assertArrayHasKey('tool_calls', $messageArray);
        $this->assertEquals($complexToolCalls, $messageArray['tool_calls']);
    }

    public function testGetterMethodsShouldReturnCorrectValues(): void
    {
        $functionCall = ['name' => 'test_function'];
        $toolCalls = [['type' => 'test_tool']];

        $message = new ChatMessage(
            role: Role::FUNCTION,
            content: 'Function response content',
            name: 'test_function_name',
            functionCall: $functionCall,
            toolCalls: $toolCalls // @phpstan-ignore-line
        );

        $this->assertSame(Role::FUNCTION, $message->getRole());
        $this->assertSame('Function response content', $message->getContent());
        $this->assertSame('test_function_name', $message->getName());
        $this->assertSame($functionCall, $message->getFunctionCall());
        $this->assertSame($toolCalls, $message->getToolCalls());
    }
}
