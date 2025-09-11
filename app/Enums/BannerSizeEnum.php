<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Banner Size Enum
 *
 * Defines banner size ratios for advertisements.
 *
 * @OA\Schema(
 *     schema="BannerSizeEnum",
 *     @OA\Property(property="value", type="string", enum={"1x1", "16x9", "4x3"}),
 *     @OA\Property(property="label", type="string"),
 * ),
 */
enum BannerSizeEnum: string
{
    use EnumToArray;

    case S1X1  = '1x1';
    case S16X9 = '16x9';
    case S4X3  = '4x3';

    public static function options(): array
    {
        return [
            [
                'label' => '1x1',
                'value' => self::S1X1->value,
            ],
            [
                'label' => '16x9',
                'value' => self::S16X9->value,
            ],
            [
                'label' => '4x3',
                'value' => self::S4X3->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::S1X1  => '1x1',
            self::S16X9 => '16x9',
            self::S4X3  => '4x3',
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
