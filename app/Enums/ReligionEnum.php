<?php

declare(strict_types=1);

namespace App\Enums;

enum ReligionEnum: string
{
    use EnumToArray;
    case ISLAM        = 'islam';
    case CHRISTIANITY = 'christianity';
    case OTHER        = 'other';

    public function title()
    {
        return match ($this) {
            self::ISLAM => 'اسلام',
            self::CHRISTIANITY => 'مسیحیت',
            self::OTHER => 'دیگری'
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
