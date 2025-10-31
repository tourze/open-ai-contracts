<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum ModelType: string implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case CHAT = 'chat';
    case COMPLETION = 'completion';
    case EMBEDDING = 'embedding';
    case AUDIO = 'audio';
    case IMAGE = 'image';
    case MODERATION = 'moderation';
    case FINE_TUNE = 'fine-tune';

    public function getLabel(): string
    {
        return match ($this) {
            self::CHAT => '对话模型',
            self::COMPLETION => '文本补全模型',
            self::EMBEDDING => '嵌入模型',
            self::AUDIO => '音频模型',
            self::IMAGE => '图像模型',
            self::MODERATION => '内容审核模型',
            self::FINE_TUNE => '微调模型',
        };
    }
}
