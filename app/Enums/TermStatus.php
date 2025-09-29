<?php

declare(strict_types=1);

namespace App\Enums;

enum TermStatus: string
{
    case DRAFT    = 'draft';
    case ACTIVE   = 'active';
    case FINISHED = 'finished';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT    => 'Draft',
            self::ACTIVE   => 'Active',
            self::FINISHED => 'Finished',
        };
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }
}
