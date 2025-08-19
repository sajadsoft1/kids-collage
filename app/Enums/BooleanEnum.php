<?php

declare(strict_types=1);

namespace App\Enums;

enum BooleanEnum: int
{
    use EnumToArray;
    case DISABLE = 0;
    case ENABLE  = 1;
    
    public static function options(): array
    {
        return [
            [
                'label' => __('core.enable'),
                'value' => self::ENABLE->value,
            ],
            [
                'label' => __('core.disable'),
                'value' => self::DISABLE->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::DISABLE => __('core.disable'),
            self::ENABLE  => __('core.enable'),
        };
    }

    public function toArray(): array
    {
        return [
            'value' => (bool) $this->value,
            'label' => $this->title(),
            'color' => match ($this) {
                self::DISABLE => 'error',
                self::ENABLE  => 'success',
            },
        ];
    }
}
