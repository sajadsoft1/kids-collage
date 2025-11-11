<?php

declare(strict_types=1);

namespace App\Enums;

enum InstallmentStatusEnum: string
{
    use EnumToArray;

    case PENDING  = 'pending';
    case PAID     = 'paid';
    case FAILED   = 'failed';

    public function title(): string
    {
        return match ($this) {
            self::PENDING => 'PENDING',
            self::PAID => 'PAID',
            self::FAILED => 'FAILED',
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
