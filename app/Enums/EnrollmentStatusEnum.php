<?php

declare(strict_types=1);

namespace App\Enums;

enum EnrollmentStatusEnum: string
{
    case PENDING = 'pending';
    case PAID    = 'paid';
    case ACTIVE  = 'active';
    case DROPPED = 'dropped';

    public function title(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PAID    => 'Paid',
            self::ACTIVE  => 'Active',
            self::DROPPED => 'Dropped',
        };
    }

    public function isActive(): bool
    {
        return match ($this) {
            self::ACTIVE => true,
            self::PENDING, self::PAID, self::DROPPED => false,
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
