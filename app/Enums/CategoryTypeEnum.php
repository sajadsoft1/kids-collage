<?php

declare(strict_types=1);

namespace App\Enums;

enum CategoryTypeEnum: string
{
    use EnumToArray;

    case BLOG      = 'blog';
    case PORTFOLIO = 'portfolio';
    case FAQ       = 'faq';
    case BULLETIN  = 'bulletin';
    case COURSE    = 'course';

    public function title(): string
    {
        return match ($this) {
            self::BLOG      => trans('category.enum.type.blog'),
            self::PORTFOLIO => trans('category.enum.type.portfolio'),
            self::FAQ       => trans('category.enum.type.faq'),
            self::BULLETIN  => trans('category.enum.type.bulletin'),
            self::COURSE    => trans('category.enum.type.course'),
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
