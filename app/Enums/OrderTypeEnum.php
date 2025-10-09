<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderTypeEnum: string
{
    use EnumToArray;

    case COURSE      = 'course';

    public function title(): string
    {
        return match ($this) {
            self::COURSE => trans('order.enum.type.course'),
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
