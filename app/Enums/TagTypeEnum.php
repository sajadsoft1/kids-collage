<?php

declare(strict_types=1);

namespace App\Enums;

enum TagTypeEnum: string
{
    use EnumToArray;
    case SPECIAL = 'special';

    public function title(): string
    {
        return match ($this) {
            self::SPECIAL => trans('tag.enum.types.special'),
        };
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->title(),
        ];
    }
}
