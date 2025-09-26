<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatusEnum: string
{
    use EnumToArray;

    case PENDING  = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED  = 'completed';
    case CANCELLED  = 'cancelled';

    public function title(): string
    {
        return match ($this) {
            self::PENDING  => 'PENDING',
            self::PROCESSING => 'PROCESSING',
            self::COMPLETED  => 'COMPLETED',
            self::CANCELLED  => 'CANCELLED',
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
