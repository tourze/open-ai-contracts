<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\OpenAiContracts\Enum\FinishReason;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;

/**
 * @internal
 */
#[CoversClass(FinishReason::class)]
final class FinishReasonTest extends AbstractEnumTestCase
{
    public function testEnumCasesValuesShouldReturnCorrectStrings(): void
    {
        $this->assertEquals('stop', FinishReason::STOP->value);
        $this->assertEquals('length', FinishReason::LENGTH->value);
        $this->assertEquals('function_call', FinishReason::FUNCTION_CALL->value);
        $this->assertEquals('content_filter', FinishReason::CONTENT_FILTER->value);
        $this->assertEquals('null', FinishReason::NULL->value);
        $this->assertEquals('tool_calls', FinishReason::TOOL_CALLS->value);
    }

    public function testEnumLabelsReturnsCorrectChinese(): void
    {
        $this->assertEquals('自然停止', FinishReason::STOP->getLabel());
        $this->assertEquals('达到最大长度', FinishReason::LENGTH->getLabel());
        $this->assertEquals('函数调用', FinishReason::FUNCTION_CALL->getLabel());
        $this->assertEquals('内容过滤', FinishReason::CONTENT_FILTER->getLabel());
        $this->assertEquals('响应中', FinishReason::NULL->getLabel());
        $this->assertEquals('工具调用', FinishReason::TOOL_CALLS->getLabel());
    }

    public function testCasesShouldReturnAllAvailableOptions(): void
    {
        $cases = FinishReason::cases();

        $this->assertCount(6, $cases);
        $this->assertContains(FinishReason::STOP, $cases);
        $this->assertContains(FinishReason::LENGTH, $cases);
        $this->assertContains(FinishReason::FUNCTION_CALL, $cases);
        $this->assertContains(FinishReason::CONTENT_FILTER, $cases);
        $this->assertContains(FinishReason::NULL, $cases);
        $this->assertContains(FinishReason::TOOL_CALLS, $cases);
    }

    public function testToArrayShouldReturnValueLabelPairs(): void
    {
        $array = FinishReason::STOP->toArray();

        $this->assertArrayHasKey('value', $array);
        $this->assertArrayHasKey('label', $array);
        $this->assertEquals('stop', $array['value']);
        $this->assertEquals('自然停止', $array['label']);
    }

    public function testGenOptionsShouldReturnSelectOptions(): void
    {
        $options = FinishReason::genOptions();

        $this->assertCount(6, $options);

        foreach ($options as $option) {
            $this->assertArrayHasKey('value', $option);
            $this->assertArrayHasKey('label', $option);
            $this->assertArrayHasKey('text', $option);
            $this->assertArrayHasKey('name', $option);
        }

        $values = array_column($options, 'value');
        $this->assertContains('stop', $values);
        $this->assertContains('length', $values);
        $this->assertContains('function_call', $values);
        $this->assertContains('content_filter', $values);
        $this->assertContains('null', $values);
        $this->assertContains('tool_calls', $values);
    }

    public function testEnumValueUniquenessShouldEnsureAllValuesAreDistinct(): void
    {
        $values = array_map(fn ($case) => $case->value, FinishReason::cases());
        $this->assertSame(count($values), count(array_unique($values)), '所有枚举的 value 必须是唯一的。');
    }

    public function testEnumLabelUniquenessShouldEnsureAllLabelsAreDistinct(): void
    {
        $labels = array_map(fn ($case) => $case->getLabel(), FinishReason::cases());
        $this->assertSame(count($labels), count(array_unique($labels)), '所有枚举的 label 必须是唯一的。');
    }
}
