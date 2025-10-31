<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\OpenAiContracts\Enum\ModelType;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;

/**
 * @internal
 */
#[CoversClass(ModelType::class)]
final class ModelTypeTest extends AbstractEnumTestCase
{
    public function testEnumCasesValuesShouldReturnCorrectStrings(): void
    {
        $this->assertEquals('chat', ModelType::CHAT->value);
        $this->assertEquals('completion', ModelType::COMPLETION->value);
        $this->assertEquals('embedding', ModelType::EMBEDDING->value);
        $this->assertEquals('audio', ModelType::AUDIO->value);
        $this->assertEquals('image', ModelType::IMAGE->value);
        $this->assertEquals('moderation', ModelType::MODERATION->value);
        $this->assertEquals('fine-tune', ModelType::FINE_TUNE->value);
    }

    public function testEnumLabelsReturnsCorrectChinese(): void
    {
        $this->assertEquals('对话模型', ModelType::CHAT->getLabel());
        $this->assertEquals('文本补全模型', ModelType::COMPLETION->getLabel());
        $this->assertEquals('嵌入模型', ModelType::EMBEDDING->getLabel());
        $this->assertEquals('音频模型', ModelType::AUDIO->getLabel());
        $this->assertEquals('图像模型', ModelType::IMAGE->getLabel());
        $this->assertEquals('内容审核模型', ModelType::MODERATION->getLabel());
        $this->assertEquals('微调模型', ModelType::FINE_TUNE->getLabel());
    }

    public function testCasesShouldReturnAllAvailableOptions(): void
    {
        $cases = ModelType::cases();

        $this->assertCount(7, $cases);
        $this->assertContains(ModelType::CHAT, $cases);
        $this->assertContains(ModelType::COMPLETION, $cases);
        $this->assertContains(ModelType::EMBEDDING, $cases);
        $this->assertContains(ModelType::AUDIO, $cases);
        $this->assertContains(ModelType::IMAGE, $cases);
        $this->assertContains(ModelType::MODERATION, $cases);
        $this->assertContains(ModelType::FINE_TUNE, $cases);
    }

    public function testToArrayShouldReturnValueLabelPairs(): void
    {
        $array = ModelType::CHAT->toArray();

        $this->assertArrayHasKey('value', $array);
        $this->assertArrayHasKey('label', $array);
        $this->assertEquals('chat', $array['value']);
        $this->assertEquals('对话模型', $array['label']);
    }

    public function testGenOptionsShouldReturnSelectOptions(): void
    {
        $options = ModelType::genOptions();

        $this->assertCount(7, $options);

        foreach ($options as $option) {
            $this->assertArrayHasKey('value', $option);
            $this->assertArrayHasKey('label', $option);
            $this->assertArrayHasKey('text', $option);
            $this->assertArrayHasKey('name', $option);
        }

        $values = array_column($options, 'value');
        $this->assertContains('chat', $values);
        $this->assertContains('completion', $values);
        $this->assertContains('embedding', $values);
        $this->assertContains('audio', $values);
        $this->assertContains('image', $values);
        $this->assertContains('moderation', $values);
        $this->assertContains('fine-tune', $values);
    }

    public function testEnumValueUniquenessShouldEnsureAllValuesAreDistinct(): void
    {
        $values = array_map(fn ($case) => $case->value, ModelType::cases());
        $this->assertSame(count($values), count(array_unique($values)), '所有枚举的 value 必须是唯一的。');
    }

    public function testEnumLabelUniquenessShouldEnsureAllLabelsAreDistinct(): void
    {
        $labels = array_map(fn ($case) => $case->getLabel(), ModelType::cases());
        $this->assertSame(count($labels), count(array_unique($labels)), '所有枚举的 label 必须是唯一的。');
    }
}
