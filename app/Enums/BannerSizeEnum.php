<?php

declare(strict_types=1);

namespace App\Enums;

enum BannerSizeEnum: string
{
    use EnumToArray;

    case S1X1  = '1x1';
    case S16X9 = '16x9';
    case S4X3  = '4x3';

    public static function getSizes(): array
    {
        return [
            [
                'label' => '1x1',
                'value' => self::S1X1,
            ],
            [
                'label' => '16x9',
                'value' => self::S16X9,
            ],
            [
                'label' => '4x3',
                'value' => self::S4X3,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::S1X1 => '1x1',
            self::S16X9 => '16x9',
            self::S4X3 => '4x3',
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
