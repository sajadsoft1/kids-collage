<?php

declare(strict_types=1);

namespace App\Enums;

enum ResourceType: string
{
    case PDF   = 'pdf';
    case VIDEO = 'video';
    case IMAGE = 'image';
    case LINK  = 'link';

    public function label(): string
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
