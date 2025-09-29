<?php

declare(strict_types=1);

namespace App\Enums;

enum SessionType: string
{
    case IN_PERSON = 'in-person';
    case ONLINE    = 'online';
    case HYBRID    = 'hybrid';

    public function label(): string
    {
        return match ($this) {
            self::IN_PERSON => 'In Person',
            self::ONLINE    => 'Online',
            self::HYBRID    => 'Hybrid',
        };
    }
}
