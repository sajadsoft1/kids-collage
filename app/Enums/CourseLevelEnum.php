<?php

declare(strict_types=1);

namespace App\Enums;

enum CourseLevelEnum: string
{
    use EnumToArray;

    case BIGGINER  = 'bigginer';
    case NORMAL    = 'normal';
    case ADVANCE   = 'advance';

    public static function options(): array
    {
        return [
            [
                'label' => 'BIGGINER',
                'value' => self::BIGGINER->value,
            ],
            [
                'label' => 'NORMAL',
                'value' => self::NORMAL->value,
            ],
            [
                'label' => 'ADVANCE',
                'value' => self::ADVANCE->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::BIGGINER => 'bigginer',
            self::NORMAL   => 'normal',
            self::ADVANCE  => 'advance',
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
