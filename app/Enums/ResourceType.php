<?php

declare(strict_types=1);

namespace App\Enums;

enum ResourceType: string
{
    use EnumToArray;

    case PDF   = 'pdf';
    case VIDEO = 'video';
    case IMAGE = 'image';
    case AUDIO = 'audio';
    case FILE  = 'file';
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
                'label' => self::AUDIO->title(),
                'value' => self::AUDIO->value,
            ],
            [
                'label' => self::FILE->title(),
                'value' => self::FILE->value,
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
            self::PDF => 'PDF Document',
            self::VIDEO => 'Video',
            self::IMAGE => 'Image',
            self::AUDIO => 'Audio',
            self::FILE => 'File',
            self::LINK => 'Link',
        };
    }

    public function isMedia(): bool
    {
        return match ($this) {
            self::VIDEO, self::IMAGE, self::AUDIO => true,
            self::PDF, self::FILE, self::LINK => false,
        };
    }

    public function requiresSignedUrl(): bool
    {
        return match ($this) {
            self::VIDEO, self::PDF, self::AUDIO, self::FILE => true,
            self::IMAGE, self::LINK => false,
        };
    }

    public function acceptedMimeTypes(): array
    {
        return match ($this) {
            self::PDF => ['application/pdf'],
            self::VIDEO => ['video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/mkv'],
            self::IMAGE => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
            self::AUDIO => ['audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a'],
            self::FILE => ['application/zip', 'application/x-rar', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            self::LINK => [],
        };
    }
}
