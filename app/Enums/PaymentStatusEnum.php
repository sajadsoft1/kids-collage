<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatusEnum: string
{
    use EnumToArray;

    case PENDING  = 'pending';
    case PAID     = 'paid';
    case FAILED   = 'failed';

    public static function options(): array
    {
        return [
            [
                'value' => self::PENDING->value,
                'label' => self::PENDING->title(),
            ],
            [
                'value' => self::PAID->value,
                'label' => self::PAID->title(),
            ],
            [
                'value' => self::FAILED->value,
                'label' => self::FAILED->title(),
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::PENDING => 'PENDING',
            self::PAID    => 'PAID',
            self::FAILED  => 'FAILED',
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
