<?php

declare(strict_types=1);

namespace App\Enums;

enum YesNoEnum: int
{
    use EnumToArray;
    case YES = 1;
    case NO  = 0;

    public static function options(): array
    {
        return [
            [
                'label' => __('general.yes'),
                'value' => self::YES,
            ],
            [
                'label' => __('general.no'),
                'value' => self::NO,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::YES => trans('general.yes'),
            self::NO  => trans('general.no'),
        };
    }

    public function toArray(): array
    {
        return [
            'value' => (bool) $this->value,
            'label' => $this->title(),
            'color' => match ($this) {
                self::NO  => 'error',
                self::YES => 'success',
            },
        ];
    }
}
