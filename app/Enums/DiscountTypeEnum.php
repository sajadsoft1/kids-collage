<?php

declare(strict_types=1);

namespace App\Enums;

enum DiscountTypeEnum: string
{
    use EnumToArray;

    case PERCENTAGE = 'percentage';
    case AMOUNT = 'amount';

    public static function options(): array
    {
        return [
            [
                'value' => self::PERCENTAGE->value,
                'label' => self::PERCENTAGE->title(),
            ],
            [
                'value' => self::AMOUNT->value,
                'label' => self::AMOUNT->title(),
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::PERCENTAGE => trans('discount.enum.type.percentage'),
            self::AMOUNT => trans('discount.enum.type.amount'),
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
