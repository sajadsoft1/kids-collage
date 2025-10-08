<?php

declare(strict_types=1);

namespace App\Enums;

enum DiscountTypeEnum: string
{
    use EnumToArray;

    case PERCENTAGE = 'percentage';
    case AMOUNT     = 'amount';

    public function title(): string
    {
        return match ($this) {
            self::PERCENTAGE => 'Percentage',
            self::AMOUNT     => 'Fixed Amount',
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
