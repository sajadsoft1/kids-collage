<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Boolean Enum
 *
 * Defines boolean states for enable/disable functionality.
 *
 * @OA\Schema(
 *     schema="BooleanEnum",
 *     @OA\Property(property="value", type="boolean", default=1, enum={1, 0}),
 *     @OA\Property(property="label", type="string", default="Enable"),
 *     @OA\Property(property="color", type="string", default="success"),
 * ),
 */
enum BooleanEnum: int
{
    use EnumToArray;
    case DISABLE = 0;
    case ENABLE  = 1;

    public static function options(): array
    {
        return [
            [
                'label' => __('core.enable'),
                'value' => self::ENABLE->value,
            ],
            [
                'label' => __('core.disable'),
                'value' => self::DISABLE->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::DISABLE => __('core.disable'),
            self::ENABLE  => __('core.enable'),
        };
    }

    public function toArray(): array
    {
        return [
            'value' => (bool) $this->value,
            'label' => $this->title(),
            'color' => match ($this) {
                self::DISABLE => 'error',
                self::ENABLE  => 'success',
            },
        ];
    }
}
