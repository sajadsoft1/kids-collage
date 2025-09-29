<?php

declare(strict_types=1);

namespace App\Enums;

enum EnrollmentStatus: string
{
    case PENDING = 'pending';
    case PAID    = 'paid';
    case ACTIVE  = 'active';
    case DROPPED = 'dropped';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PAID    => 'Paid',
            self::ACTIVE  => 'Active',
            self::DROPPED => 'Dropped',
        };
    }

    public function isActive(): bool
    {
        return match ($this) {
            self::ACTIVE => true,
            self::PENDING, self::PAID, self::DROPPED => false,
        };
    }
}
