<?php

declare(strict_types=1);

namespace App\Enums;


enum TicketPriorityEnum: int
{
    use EnumToArray;

    case CRITICAL = 4;      // فوری
    case HIGH     = 3;      // بالا
    case MEDIUM   = 2;      // متوسط
    case LOW      = 1;      // پایین

    public static function options(): array
    {
        return [
            [
                'label' => __('ticket.enum.priority.critical'),
                'value' => self::CRITICAL->value,
            ],
            [
                'label' => __('ticket.enum.priority.high'),
                'value' => self::HIGH->value,
            ],
            [
                'label' => __('ticket.enum.priority.medium'),
                'value' => self::MEDIUM->value,
            ],
            [
                'label' => __('ticket.enum.priority.low'),
                'value' => self::LOW->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::CRITICAL => __('ticket.enum.priority.critical'),
            self::HIGH     => __('ticket.enum.priority.high'),
            self::MEDIUM   => __('ticket.enum.priority.medium'),
            self::LOW      => __('ticket.enum.priority.low'),
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
