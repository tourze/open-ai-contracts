<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum Role: string implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case SYSTEM = 'system';
    case USER = 'user';
    case ASSISTANT = 'assistant';
    case FUNCTION = 'function';
    case TOOL = 'tool';

    public function getLabel(): string
    {
        return match ($this) {
            self::SYSTEM => '系统消息',
            self::USER => '用户消息',
            self::ASSISTANT => '助手消息',
            self::FUNCTION => '函数消息',
            self::TOOL => '工具消息',
        };
    }
}
