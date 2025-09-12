<?php

declare(strict_types=1);

namespace App\Enums;


enum SocialMediaPositionEnum: string
{
    use EnumToArray;

    case ALL    = 'all';
    case HEADER = 'header';
    case FOOTER = 'footer';

    public static function options(): array
    {
        return [
            [
                'label' => __('socialMedia.enum.position.all'),
                'value' => self::ALL->value,
            ],
            [
                'label' => __('socialMedia.enum.position.header'),
                'value' => self::HEADER->value,
            ],
            [
                'label' => __('socialMedia.enum.position.footer'),
                'value' => self::FOOTER->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::ALL    => __('socialMedia.enum.position.all'),
            self::HEADER => __('socialMedia.enum.position.header'),
            self::FOOTER => __('socialMedia.enum.position.footer'),
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
