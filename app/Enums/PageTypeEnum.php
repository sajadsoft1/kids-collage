<?php

declare(strict_types=1);

namespace App\Enums;

enum PageTypeEnum: string
{
    use EnumToArray;

    case RULES    = 'rules';
    case ABOUT_US = 'about-us';

    public static function options(): array
    {
        return [
            [
                'label' => __('page.enum.type.rules'),
                'value' => self::RULES->value,
            ],
            [
                'label' => __('page.enum.type.about-us'),
                'value' => self::ABOUT_US->value,
            ],
        ];
    }

    public function title(?string $locale = null): string
    {
        return match ($this) {
            self::RULES    => __('page.enum.type.rules', locale: $locale),
            self::ABOUT_US => __('page.enum.type.about-us', locale: $locale),
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
