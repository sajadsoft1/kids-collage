<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Comment Type Enum
 *
 * Defines comment types for the system.
 *
 * @OA\Schema(
 *     schema="CommentTypeEnum",
 *     @OA\Property(property="value", type="string", enum={"normal", "special"}),
 *     @OA\Property(property="title", type="string"),
 * ),
 */
enum CommentTypeEnum: string
{
    use EnumToArray;
    case NORMAL  = 'normal';
    case SPECIAL = 'special';

    public static function options(): array
    {
        return [
            [
                'label' => trans('comment.enum.type.normal'),
                'value' => self::NORMAL->value,
            ],
            [
                'label' => trans('comment.enum.type.special'),
                'value' => self::SPECIAL->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::NORMAL  => trans('comment.enum.type.normal'),
            self::SPECIAL => trans('comment.enum.type.special'),
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
