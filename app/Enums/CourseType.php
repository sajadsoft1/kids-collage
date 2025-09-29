<?php

declare(strict_types=1);

namespace App\Enums;

enum CourseType: string
{
    case IN_PERSON  = 'in-person';
    case ONLINE     = 'online';
    case HYBRID     = 'hybrid';
    case SELF_PACED = 'self-paced';

    public function label(): string
    {
        return match ($this) {
            self::IN_PERSON  => 'In Person',
            self::ONLINE     => 'Online',
            self::HYBRID     => 'Hybrid',
            self::SELF_PACED => 'Self-Paced',
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
}
