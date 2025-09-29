<?php

declare(strict_types=1);

namespace App\Enums;

enum CourseStatus: string
{
    case DRAFT     = 'draft';
    case SCHEDULED = 'scheduled';
    case ACTIVE    = 'active';
    case FINISHED  = 'finished';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT     => 'Draft',
            self::SCHEDULED => 'Scheduled',
            self::ACTIVE    => 'Active',
            self::FINISHED  => 'Finished',
            self::CANCELLED => 'Cancelled',
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
