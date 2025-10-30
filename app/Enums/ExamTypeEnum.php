<?php

declare(strict_types=1);

namespace App\Enums;

enum ExamTypeEnum: string
{
    use EnumToArray;

    case SCORED = 'scored';
    case SURVEY = 'survey';

    public function title(): string
    {
        return match ($this) {
            self::SCORED => 'نمره‌دار',
            self::SURVEY => 'نظرسنجی',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::SCORED => 'آزمونی که نمره‌دهی می‌شود و نتیجه دارد',
            self::SURVEY => 'نظرسنجی بدون نمره‌دهی',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::SCORED => 'heroicon-o-calculator',
            self::SURVEY => 'heroicon-o-clipboard-document-list',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SCORED => 'blue',
            self::SURVEY => 'purple',
        };
    }

    public function requiresScoring(): bool
    {
        return $this === self::SCORED;
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->title(),
        ])->toArray();
    }
}
