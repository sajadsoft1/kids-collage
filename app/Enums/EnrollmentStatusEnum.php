<?php

declare(strict_types=1);

namespace App\Enums;

enum EnrollmentStatusEnum: string
{
    use EnumToArray;

    case ACTIVE        = 'active';
    case CANCELLED     = 'cancelled';
    case COMPLETED     = 'completed';

    public function title(): string
    {
        return match ($this) {
            self::ACTIVE    => 'ACTIVE',
            self::CANCELLED => 'CANCELLED',
            self::COMPLETED => 'COMPLETED',
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
