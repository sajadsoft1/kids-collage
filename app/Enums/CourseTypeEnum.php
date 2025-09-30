<?php

declare(strict_types=1);

namespace App\Enums;

enum CourseTypeEnum: string
{
    use EnumToArray;

    case IN_PERSON  = 'in-person';
    case ONLINE     = 'online';
    case HYBRID     = 'hybrid';
    case SELF_PACED = 'self-paced';

    public static function options(): array
    {
        return [
            [
                'label' => 'IN_PERSON',
                'value' => self::IN_PERSON->value,
            ],
            [
                'label' => 'ONLINE',
                'value' => self::ONLINE->value,
            ],
            [
                'label' => 'HYBRID',
                'value' => self::HYBRID->value,
            ],
            [
                'label' => 'SELF_PACED',
                'value' => self::SELF_PACED->value,
            ],
        ];
    }

    public function title(): string
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

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->title(),
        ];
    }
}
