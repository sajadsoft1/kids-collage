<?php

declare(strict_types=1);

namespace App\Enums;

enum TermStatus: string
{
    use EnumToArray;

    case DRAFT    = 'draft';
    case ACTIVE   = 'active';
    case FINISHED = 'finished';

    public static function options(): array
    {
        return [
            [
                'label' => self::DRAFT->title(),
                'value' => self::DRAFT->value,
            ],
            [
                'label' => self::ACTIVE->title(),
                'value' => self::ACTIVE->value,
            ],
            [
                'label' => self::FINISHED->title(),
                'value' => self::FINISHED->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::DRAFT => 'پیش‌نویس',
            self::ACTIVE => 'فعال',
            self::FINISHED => 'تمام شده',
        };
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }
}
