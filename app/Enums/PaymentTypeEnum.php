<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentTypeEnum: string
{
    use EnumToArray;

    case FULL_ONLINE        = 'full_online';
    case PART_IAL_SCHEDULED = 'partial_scheduled';
    case FULL_SCHEDULED     = 'full_scheduled';

    public function title(): string
    {
        return match ($this) {
            self::FULL_ONLINE        => 'FULL_ONLINE',
            self::PART_IAL_SCHEDULED => 'PART_IAL_SCHEDULED',
            self::FULL_SCHEDULED     => 'FULL_SCHEDULED',
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
