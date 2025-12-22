<?php

declare(strict_types=1);

namespace App\Enums;

enum BranchStatusEnum: string
{
    use EnumToArray;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public static function options(): array
    {
        return [
            [
                'label' => self::ACTIVE->title(),
                'value' => self::ACTIVE->value,
            ],
            [
                'label' => self::INACTIVE->title(),
                'value' => self::INACTIVE->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::ACTIVE => __('branch.status.active'),
            self::INACTIVE => __('branch.status.inactive'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'error',
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
