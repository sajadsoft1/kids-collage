<?php

declare(strict_types=1);

namespace App\Enums;

enum CourseTypeEnum: string
{
    use EnumToArray;

    case IN_PERSON  = 'in-person';
    case ONLINE     = 'online';
    case HYBRID     = 'hybrid';
    case SELF_PACED = 'self-paced';

    public static function options(): array
    {
        return [
            [
                'label' => trans('course.enum.type.in_person'),
                'value' => self::IN_PERSON->value,
            ],
            [
                'label' => trans('course.enum.type.online'),
                'value' => self::ONLINE->value,
            ],
            [
                'label' => trans('course.enum.type.hybrid'),
                'value' => self::HYBRID->value,
            ],
            [
                'label' => trans('course.enum.type.self_paced'),
                'value' => self::SELF_PACED->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::IN_PERSON => trans('course.enum.type.in_person'),
            self::ONLINE => trans('course.enum.type.online'),
            self::HYBRID => trans('course.enum.type.hybrid'),
            self::SELF_PACED => trans('course.enum.type.self_paced'),
        };
    }

    public function requiresSchedule(): bool
    {
        return match ($this) {
            self::IN_PERSON, self::ONLINE, self::HYBRID => true,
            self::SELF_PACED => false,
        };
    }

    public function requiresRoom(): bool
    {
        return match ($this) {
            self::IN_PERSON, self::HYBRID => true,
            self::ONLINE, self::SELF_PACED => false,
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
