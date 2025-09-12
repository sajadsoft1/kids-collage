<?php

declare(strict_types=1);

namespace App\Enums;


enum TicketStatusEnum: string
{
    use EnumToArray;

    case OPEN  = 'open';
    case CLOSE = 'close';

    public static function options(): array
    {
        return [
            [
                'label' => __('ticket.enum.status.open'),
                'value' => self::OPEN->value,
            ],
            [
                'label' => __('ticket.enum.status.close'),
                'value' => self::CLOSE->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::OPEN  => __('ticket.enum.status.open'),
            self::CLOSE => __('ticket.enum.status.close'),
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
