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
            self::PENDING => trans('enrollment.enum.status.pending'),
            self::PAID => trans('enrollment.enum.status.paid'),
            self::ACTIVE => trans('enrollment.enum.status.active'),
            self::DROPPED => trans('enrollment.enum.status.dropped'),
        };
    }

    public function isActive(): bool
    {
        return match ($this) {
            self::ACTIVE => true,
            self::PENDING, self::PAID, self::DROPPED => false,
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'secondary',
            self::PAID => 'accent',
            self::ACTIVE => 'success',
            self::DROPPED => 'danger',
        };
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->title(),
            'color' => $this->color(),
        ];
    }
}
