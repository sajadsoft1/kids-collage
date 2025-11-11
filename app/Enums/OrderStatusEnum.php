<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatusEnum: string
{
    use EnumToArray;

    case PENDING    = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED  = 'completed';
    case CANCELLED  = 'cancelled';

    public static function courseOptions(): array
    {
        return [
            [
                'value' => self::PENDING->value,
                'label' => self::PENDING->title(),
            ],
            [
                'value' => self::COMPLETED->value,
                'label' => self::COMPLETED->title(),
            ],
            [
                'value' => self::CANCELLED->value,
                'label' => self::CANCELLED->title(),
            ],
        ];
    }

    public static function options(): array
    {
        return [
            [
                'value' => self::PENDING->value,
                'label' => self::PENDING->title(),
            ],
            [
                'value' => self::PROCESSING->value,
                'label' => self::PROCESSING->title(),
            ],
            [
                'value' => self::COMPLETED->value,
                'label' => self::COMPLETED->title(),
            ],
            [
                'value' => self::CANCELLED->value,
                'label' => self::CANCELLED->title(),
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::PENDING => trans('order.enum.status.pending'),
            self::PROCESSING => trans('order.enum.status.processing'),
            self::COMPLETED => trans('order.enum.status.completed'),
            self::CANCELLED => trans('order.enum.status.cancelled'),
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
