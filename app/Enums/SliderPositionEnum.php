<?php

declare(strict_types=1);

namespace App\Enums;

enum SliderPositionEnum: string
{
    use EnumToArray;

    case TOP    = 'top';
    case MIDDLE = 'middle';
    case BOTTOM = 'bottom';

    public function title(): string
    {
        return match ($this) {
            self::TOP => trans('slider.enum.position.top'),
            self::MIDDLE => trans('slider.enum.position.middle'),
            self::BOTTOM => trans('slider.enum.position.bottom'),
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
