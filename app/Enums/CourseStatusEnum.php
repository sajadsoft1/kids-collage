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

    public static function runerOptions(): array
    {
        return [
            [
                'label' => self::DRAFT->title(),
                'value' => self::DRAFT->value,
            ],
            [
                'label' => self::SCHEDULED->title(),
                'value' => self::SCHEDULED->value,
            ],
            [
                'label' => self::ACTIVE->title(),
                'value' => self::ACTIVE->value,
            ],
        ];
    }

    public static function options(): array
    {
        return [
            [
                'label' => self::DRAFT->title(),
                'value' => self::DRAFT->value,
            ],
            [
                'label' => self::SCHEDULED->title(),
                'value' => self::SCHEDULED->value,
            ],
            [
                'label' => self::ACTIVE->title(),
                'value' => self::ACTIVE->value,
            ],
            [
                'label' => self::FINISHED->title(),
                'value' => self::FINISHED->value,
            ],
            [
                'label' => self::CANCELLED->title(),
                'value' => self::CANCELLED->value,
            ],
        ];
    }

    public function title(): string
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
