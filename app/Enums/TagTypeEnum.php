<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Tag Type Enum
 *
 * Defines tag types for content categorization.
 *
 * @OA\Schema(
 *     schema="TagTypeEnum",
 *     @OA\Property(property="value", type="string", enum={"special"}),
 *     @OA\Property(property="label", type="string"),
 * ),
 */
enum TagTypeEnum: string
{
    use EnumToArray;
    case SPECIAL   = 'special';

    public static function options(): array
    {
        return [
            [
                'label' => trans('tag.enum.types.special'),
                'value' => self::SPECIAL->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::SPECIAL => trans('tag.enum.types.special'),
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
