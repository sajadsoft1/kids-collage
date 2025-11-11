<?php

declare(strict_types=1);

namespace App\Enums;

enum SocialMediaPositionEnum: string
{
    use EnumToArray;
    
    case ALL = 'all';
    case HEADER = 'header';
    case FOOTER = 'footer';
    
    public function title(): string
    {
        return match ($this) {
            self::ALL => __('socialMedia.enum.position.all'),
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
