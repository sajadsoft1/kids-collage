<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Yes No Enum
 *
 * Defines yes/no boolean options for general use.
 *
 * @OA\Schema(
 *     schema="YesNoEnum",
 *     @OA\Property(property="value", type="boolean", default=1, enum={1, 0}),
 *     @OA\Property(property="label", type="string"),
 *     @OA\Property(property="color", type="string"),
 * ),
 */
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
                'value' => self::YES->value,
            ],
            [
                'label' => __('general.no'),
                'value' => self::NO->value,
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
