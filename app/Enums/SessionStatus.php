<?php

declare(strict_types=1);

namespace App\Enums;

enum SessionStatus: string
{
    case PLANNED   = 'planned';
    case DONE      = 'done';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PLANNED   => 'Planned',
            self::DONE      => 'Done',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function isCompleted(): bool
    {
        return $this === self::DONE;
    }
}
