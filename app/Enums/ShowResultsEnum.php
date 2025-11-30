<?php

declare(strict_types=1);

namespace App\Enums;

enum ShowResultsEnum: string
{
    use EnumToArray;

    case IMMEDIATE = 'immediate';
    case AFTER_SUBMIT = 'after_submit';
    case MANUAL = 'manual';
    case NEVER = 'never';

    public function title(): string
    {
        return match ($this) {
            self::IMMEDIATE => 'فوری (بعد از هر سوال)',
            self::AFTER_SUBMIT => 'بعد از ثبت آزمون',
            self::MANUAL => 'دستی (توسط مدرس)',
            self::NEVER => 'هرگز',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::IMMEDIATE => 'نتیجه هر سوال بلافاصله بعد از پاسخ نمایش داده می‌شود',
            self::AFTER_SUBMIT => 'نتایج بعد از تکمیل کل آزمون نمایش داده می‌شود',
            self::MANUAL => 'نتایج فقط بعد از بررسی و تایید مدرس نمایش داده می‌شود',
            self::NEVER => 'نتایج هرگز به دانش‌آموز نمایش داده نمی‌شود',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->title(),
        ])->toArray();
    }
}
