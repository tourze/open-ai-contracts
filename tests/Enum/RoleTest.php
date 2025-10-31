<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\OpenAiContracts\Enum\Role;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;

/**
 * @internal
 */
#[CoversClass(Role::class)]
final class RoleTest extends AbstractEnumTestCase
{
    public function testEnumCasesValuesShouldReturnCorrectStrings(): void
    {
        $this->assertEquals('system', Role::SYSTEM->value);
        $this->assertEquals('user', Role::USER->value);
        $this->assertEquals('assistant', Role::ASSISTANT->value);
        $this->assertEquals('function', Role::FUNCTION->value);
        $this->assertEquals('tool', Role::TOOL->value);
    }

    public function testEnumLabelsReturnsCorrectChinese(): void
    {
        $this->assertEquals('系统消息', Role::SYSTEM->getLabel());
        $this->assertEquals('用户消息', Role::USER->getLabel());
        $this->assertEquals('助手消息', Role::ASSISTANT->getLabel());
        $this->assertEquals('函数消息', Role::FUNCTION->getLabel());
        $this->assertEquals('工具消息', Role::TOOL->getLabel());
    }

    public function testCasesShouldReturnAllAvailableOptions(): void
    {
        $cases = Role::cases();

        $this->assertCount(5, $cases);
        $this->assertContains(Role::SYSTEM, $cases);
        $this->assertContains(Role::USER, $cases);
        $this->assertContains(Role::ASSISTANT, $cases);
        $this->assertContains(Role::FUNCTION, $cases);
        $this->assertContains(Role::TOOL, $cases);
    }

    public function testToArrayShouldReturnValueLabelPairs(): void
    {
        $array = Role::SYSTEM->toArray();

        $this->assertArrayHasKey('value', $array);
        $this->assertArrayHasKey('label', $array);
        $this->assertEquals('system', $array['value']);
        $this->assertEquals('系统消息', $array['label']);
    }

    public function testGenOptionsShouldReturnSelectOptions(): void
    {
        $options = Role::genOptions();

        $this->assertCount(5, $options);

        foreach ($options as $option) {
            $this->assertArrayHasKey('value', $option);
            $this->assertArrayHasKey('label', $option);
            $this->assertArrayHasKey('text', $option);
            $this->assertArrayHasKey('name', $option);
        }

        $values = array_column($options, 'value');
        $this->assertContains('system', $values);
        $this->assertContains('user', $values);
        $this->assertContains('assistant', $values);
        $this->assertContains('function', $values);
        $this->assertContains('tool', $values);
    }

    public function testEnumValueUniquenessShouldEnsureAllValuesAreDistinct(): void
    {
        $values = array_map(fn ($case) => $case->value, Role::cases());
        $this->assertSame(count($values), count(array_unique($values)), '所有枚举的 value 必须是唯一的。');
    }

    public function testEnumLabelUniquenessShouldEnsureAllLabelsAreDistinct(): void
    {
        $labels = array_map(fn ($case) => $case->getLabel(), Role::cases());
        $this->assertSame(count($labels), count(array_unique($labels)), '所有枚举的 label 必须是唯一的。');
    }
}
