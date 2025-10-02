<?php

declare(strict_types=1);

namespace App\Enums;

enum SessionType: string
{
    case IN_PERSON = 'in-person';
    case ONLINE    = 'online';
    case HYBRID    = 'hybrid';

    public function title(): string
    {
        return match ($this) {
            self::IN_PERSON => trans('session.enum.type.in_person'),
            self::ONLINE    => trans('session.enum.type.online'),
            self::HYBRID    => trans('session.enum.type.hybrid'),
        };
    }
}
