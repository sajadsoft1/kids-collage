<?php

declare(strict_types=1);

namespace App\Enums;

enum AttemptStatusEnum: string
{
    use EnumToArray;

    case IN_PROGRESS = 'in_progress';
    case COMPLETED   = 'completed';
    case ABANDONED   = 'abandoned';
    case EXPIRED     = 'expired';

    public function title(): string
    {
        return match ($this) {
            self::IN_PROGRESS => 'در حال انجام',
            self::COMPLETED => 'تکمیل شده',
            self::ABANDONED => 'رها شده',
            self::EXPIRED => 'منقضی شده',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::IN_PROGRESS => 'آزمون در حال انجام است',
            self::COMPLETED => 'آزمون با موفقیت تکمیل شده',
            self::ABANDONED => 'آزمون توسط کاربر رها شده',
            self::EXPIRED => 'زمان آزمون به پایان رسیده',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::IN_PROGRESS => 'info',
            self::COMPLETED => 'success',
            self::ABANDONED => 'warning',
            self::EXPIRED => 'danger',
        };
    }

    public function bgColor(): string
    {
        return match ($this) {
            self::IN_PROGRESS => 'bg-blue-100 text-blue-800',
            self::COMPLETED => 'bg-green-100 text-green-800',
            self::ABANDONED => 'bg-yellow-100 text-yellow-800',
            self::EXPIRED => 'bg-red-100 text-red-800',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::IN_PROGRESS => 'heroicon-o-clock',
            self::COMPLETED => 'heroicon-o-check-circle',
            self::ABANDONED => 'heroicon-o-x-circle',
            self::EXPIRED => 'heroicon-o-exclamation-circle',
        };
    }

    public function isFinished(): bool
    {
        return in_array($this, [self::COMPLETED, self::ABANDONED, self::EXPIRED]);
    }

    public function canContinue(): bool
    {
        return $this === self::IN_PROGRESS;
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->title(),
        ])->toArray();
    }
}
