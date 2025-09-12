<?php

declare(strict_types=1);

namespace App\Enums;


enum TagTypeEnum: string
{
    use EnumToArray;
    case SPECIAL   = 'special';

    public static function options(): array
    {
        return [
            [
                'label' => trans('tag.enum.types.special'),
                'value' => self::SPECIAL->value,
            ],
        ];
    }

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
