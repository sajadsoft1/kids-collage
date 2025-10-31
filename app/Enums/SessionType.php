<?php

declare(strict_types=1);

namespace App\Enums;

enum SessionType: string
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
                'label' => self::IN_PERSON->title(),
                'value' => self::IN_PERSON->value,
            ],
            [
                'label' => self::ONLINE->title(),
                'value' => self::ONLINE->value,
            ],
            [
                'label' => self::HYBRID->title(),
                'value' => self::HYBRID->value,
            ],
            [
                'label' => self::SELF_PACED->title(),
                'value' => self::SELF_PACED->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::IN_PERSON  => trans('session.enum.type.in_person'),
            self::ONLINE     => trans('session.enum.type.online'),
            self::HYBRID     => trans('session.enum.type.hybrid'),
            self::SELF_PACED => trans('session.enum.type.self_paced'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::IN_PERSON  => 'success',
            self::ONLINE     => 'primary',
            self::HYBRID     => 'warning',
            self::SELF_PACED => 'info',
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
