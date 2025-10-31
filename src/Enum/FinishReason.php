<?php

declare(strict_types=1);

namespace Tourze\OpenAiContracts\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum FinishReason: string implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case STOP = 'stop';
    case LENGTH = 'length';
    case FUNCTION_CALL = 'function_call';
    case CONTENT_FILTER = 'content_filter';
    case NULL = 'null';
    case TOOL_CALLS = 'tool_calls';

    public function getLabel(): string
    {
        return match ($this) {
            self::STOP => '自然停止',
            self::LENGTH => '达到最大长度',
            self::FUNCTION_CALL => '函数调用',
            self::CONTENT_FILTER => '内容过滤',
            self::NULL => '响应中',
            self::TOOL_CALLS => '工具调用',
        };
    }
}
