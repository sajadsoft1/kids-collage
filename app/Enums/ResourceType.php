<?php

declare(strict_types=1);

namespace App\Enums;

enum ResourceType: string
{
    case PDF   = 'pdf';
    case VIDEO = 'video';
    case IMAGE = 'image';
    case LINK  = 'link';

    public static function options(): array
    {
        return [
            [
                'label' => self::PDF->title(),
                'value' => self::PDF->value,
            ],
            [
                'label' => self::VIDEO->title(),
                'value' => self::VIDEO->value,
            ],
            [
                'label' => self::IMAGE->title(),
                'value' => self::IMAGE->value,
            ],
            [
                'label' => self::LINK->title(),
                'value' => self::LINK->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::PDF   => 'PDF Document',
            self::VIDEO => 'Video',
            self::IMAGE => 'Image',
            self::LINK  => 'Link',
        };
    }

    public function isMedia(): bool
    {
        return match ($this) {
            self::VIDEO, self::IMAGE => true,
            self::PDF, self::LINK => false,
        };
    }

    public function requiresSignedUrl(): bool
    {
        return match ($this) {
            self::VIDEO, self::PDF => true,
            self::IMAGE, self::LINK => false,
        };
    }
}
