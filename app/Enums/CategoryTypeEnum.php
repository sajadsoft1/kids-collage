<?php

declare(strict_types=1);

namespace App\Enums;

enum CategoryTypeEnum: string
{
    use EnumToArray;

    case BLOG      = 'blog';
    case PORTFOLIO = 'portfolio';
    case FAQ       = 'faq';

    public function title(): string
    {
        return match ($this) {
            self::BLOG      => trans('category.enum.type.blog'),
            self::PORTFOLIO => trans('category.enum.type.portfolio'),
            self::FAQ       => trans('category.enum.type.faq'),
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
