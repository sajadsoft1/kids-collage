<?php

declare(strict_types=1);

namespace App\Enums;

enum CommentTypeEnum: string
{
    use EnumToArray;
    case NORMAL  = 'normal';
    case SPECIAL = 'special';

    public function title(): string
    {
        return match ($this) {
            self::NORMAL  => trans('comment.enum.type.normal'),
            self::SPECIAL => trans('comment.enum.type.special'),
        };
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title(),
            'value' => $this->value,
        ];
    }
}
