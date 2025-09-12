<?php

declare(strict_types=1);

namespace App\Enums;

enum CategoryTypeEnum: string
{
    use EnumToArray;

    case BLOG      = 'blog';
    case PORTFOLIO = 'portfolio';
    case FAQ       = 'faq';

    public static function options(): array
    {
        return [
            [
                'label' => trans('category.enum.type.blog'),
                'value' => self::BLOG->value,
            ],
            [
                'label' => trans('category.enum.type.portfolio'),
                'value' => self::PORTFOLIO->value,
            ],
            [
                'label' => trans('category.enum.type.faq'),
                'value' => self::FAQ->value,
            ],
        ];
    }

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
