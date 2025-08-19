<?php

declare(strict_types=1);

namespace App\Enums;

enum GenderEnum: int
{
    use EnumToArray;
    case MEN   = 1;
    case WOMEN = 0;

    public function title()
    {
        return match ($this) {
            self::MEN   => 'مرد',
            self::WOMEN => 'زن'
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
