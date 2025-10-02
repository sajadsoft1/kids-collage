<?php

declare(strict_types=1);

namespace App\Enums;

enum CourseStatusEnum: string
{
    case DRAFT     = 'draft';
    case SCHEDULED = 'scheduled';
    case ACTIVE    = 'active';
    case FINISHED  = 'finished';
    case CANCELLED = 'cancelled';

    public static function options(): array
    {
        return [
            [
                'label' => trans('course.enum.status.draft'),
                'value' => self::DRAFT->value,
            ],
            [
                'label' => trans('course.enum.status.scheduled'),
                'value' => self::SCHEDULED->value,
            ],
            [
                'label' => trans('course.enum.status.active'),
                'value' => self::ACTIVE->value,
            ],
            [
                'label' => trans('course.enum.status.finished'),
                'value' => self::FINISHED->value,
            ],
            [
                'label' => trans('course.enum.status.cancelled'),
                'value' => self::CANCELLED->value,
            ],
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::DRAFT     => trans('course.enum.status.draft'),
            self::SCHEDULED => trans('course.enum.status.scheduled'),
            self::ACTIVE    => trans('course.enum.status.active'),
            self::FINISHED  => trans('course.enum.status.finished'),
            self::CANCELLED => trans('course.enum.status.cancelled'),
        };
    }

    public function isActive(): bool
    {
        return match ($this) {
            self::ACTIVE => true,
            self::DRAFT, self::SCHEDULED, self::FINISHED, self::CANCELLED => false,
        };
    }

    public function canEnroll(): bool
    {
        return match ($this) {
            self::SCHEDULED, self::ACTIVE => true,
            self::DRAFT, self::FINISHED, self::CANCELLED => false,
        };
    }
}
