<?php

declare(strict_types=1);

namespace App\Enums;

enum SessionStatus: string
{
    case PLANNED   = 'planned';
    case DONE      = 'done';
    case CANCELLED = 'cancelled';

    public function title(): string
    {
        return match ($this) {
            self::PLANNED => trans('session.enum.status.planned'),
            self::DONE => trans('session.enum.status.done'),
            self::CANCELLED => trans('session.enum.status.cancelled'),
        };
    }

    public function isCompleted(): bool
    {
        return $this === self::DONE;
    }
}
