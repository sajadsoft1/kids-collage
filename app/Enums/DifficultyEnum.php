<?php

declare(strict_types=1);

namespace App\Enums;

enum DifficultyEnum: string
{
    use EnumToArray;

    case EASY   = 'easy';
    case MEDIUM = 'medium';
    case HARD   = 'hard';

    public function title(): string
    {
        return match ($this) {
            self::EASY   => 'آسان',
            self::MEDIUM => 'متوسط',
            self::HARD   => 'سخت',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::EASY   => 'success',
            self::MEDIUM => 'warning',
            self::HARD   => 'danger',
        };
    }

    public function bgColor(): string
    {
        return match ($this) {
            self::EASY   => 'bg-green-100 text-green-800',
            self::MEDIUM => 'bg-yellow-100 text-yellow-800',
            self::HARD   => 'bg-red-100 text-red-800',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::EASY   => 'heroicon-o-face-smile',
            self::MEDIUM => 'heroicon-o-face-frown',
            self::HARD   => 'heroicon-o-fire',
        };
    }

    public function toArray(): array
    {
        return [
            'value'   => $this->value,
            'label'   => $this->title(),
            'color'   => $this->color(),
            'icon'    => $this->icon(),
            'bgColor' => $this->bgColor(),
        ];
    }

    public function suggestedScore(): float
    {
        return match ($this) {
            self::EASY   => 1.0,
            self::MEDIUM => 2.0,
            self::HARD   => 3.0,
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [
            $case->value => $case->title(),
        ])->toArray();
    }
}
