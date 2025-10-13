<?php

declare(strict_types=1);

namespace App\Enums;

enum SessionType: string
{
    use EnumToArray;
    case IN_PERSON = 'in-person';
    case ONLINE    = 'online';
    case HYBRID    = 'hybrid';

    public static function options(): array
    {
        return [
            [
                'label' => trans('session.enum.type.in_person'),
                'value' => self::IN_PERSON->value,
            ],
            [
                'label' => trans('session.enum.type.online'),
                'value' => self::ONLINE->value,
            ],
            [
                'label' => trans('session.enum.type.hybrid'),
                'value' => self::HYBRID->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::IN_PERSON => trans('session.enum.type.in_person'),
            self::ONLINE    => trans('session.enum.type.online'),
            self::HYBRID    => trans('session.enum.type.hybrid'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::IN_PERSON => 'success',
            self::ONLINE    => 'primary',
            self::HYBRID    => 'warning',
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
