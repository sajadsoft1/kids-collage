<?php

declare(strict_types=1);

namespace App\Enums;

enum PageTypeEnum: string
{
    use EnumToArray;

    case RULES    = 'rules';
    case ABOUT_US = 'about-us';

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
