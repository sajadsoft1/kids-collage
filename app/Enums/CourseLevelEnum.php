<?php

declare(strict_types=1);

namespace App\Enums;

enum CourseLevelEnum: string
{
    use EnumToArray;

    case BIGGINER = 'bigginer';
    case NORMAL = 'normal';
    case ADVANCE = 'advance';
    case INTERMEDIATE = 'intermediate';

    public static function options(): array
    {
        return [
            [
                'label' => self::BIGGINER->title(),
                'value' => self::BIGGINER->value,
            ],
            [
                'label' => self::NORMAL->title(),
                'value' => self::NORMAL->value,
            ],
            [
                'label' => self::ADVANCE->title(),
                'value' => self::ADVANCE->value,
            ],
            [
                'label' => self::INTERMEDIATE->title(),
                'value' => self::INTERMEDIATE->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::BIGGINER => trans('course.enum.level.bigginer'),
            self::NORMAL => trans('course.enum.level.normal'),
            self::ADVANCE => trans('course.enum.level.advance'),
            self::INTERMEDIATE => trans('course.enum.level.intermediate'),
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
