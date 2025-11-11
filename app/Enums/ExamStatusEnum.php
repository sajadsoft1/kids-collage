<?php

declare(strict_types=1);

namespace App\Enums;

enum ExamStatusEnum: string
{
    use EnumToArray;

    case DRAFT     = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED  = 'archived';

    public function title(): string
    {
        return match ($this) {
            self::DRAFT => 'پیش‌نویس',
            self::PUBLISHED => 'منتشر شده',
            self::ARCHIVED => 'بایگانی شده',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::DRAFT => 'آزمون در حال ویرایش است',
            self::PUBLISHED => 'آزمون منتشر شده و قابل دسترسی است',
            self::ARCHIVED => 'آزمون بایگانی شده و غیرفعال است',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::PUBLISHED => 'success',
            self::ARCHIVED => 'warning',
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

    public function bgColor(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-gray-100 text-gray-800',
            self::PUBLISHED => 'bg-green-100 text-green-800',
            self::ARCHIVED => 'bg-yellow-100 text-yellow-800',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::DRAFT => 'heroicon-o-document',
            self::PUBLISHED => 'heroicon-o-check-circle',
            self::ARCHIVED => 'heroicon-o-archive-box',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::DRAFT => 'secondary',
            self::PUBLISHED => 'success',
            self::ARCHIVED => 'warning',
        };
    }

    public function canEdit(): bool
    {
        return in_array($this, [self::DRAFT, self::ARCHIVED]);
    }

    public function canPublish(): bool
    {
        return $this === self::DRAFT;
    }

    public function canArchive(): bool
    {
        return $this === self::PUBLISHED;
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->title(),
        ])->toArray();
    }
}
