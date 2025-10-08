<?php

declare(strict_types=1);

namespace App\Enums;

enum SeoRobotsMetaEnum: string
{
    use EnumToArray;

    case INDEX_FOLLOW     = 'index_follow';
    case NOINDEX_NOFOLLOW = 'noindex_nofollow';
    case NOINDEX_FOLLOW   = 'noindex_follow';

    public function title(): string
    {
        return match ($this) {
            self::INDEX_FOLLOW     => trans('seo.robots_type.index_follow.title'),
            self::NOINDEX_NOFOLLOW => trans('seo.robots_type.noindex_nofollow.title'),
            self::NOINDEX_FOLLOW   => trans('seo.robots_type.noindex_follow.title')
        };
    }

    public function hint(): string
    {
        return match ($this) {
            self::INDEX_FOLLOW     => trans('seo.robots_type.index_follow.hint'),
            self::NOINDEX_NOFOLLOW => trans('seo.robots_type.noindex_nofollow.hint'),
            self::NOINDEX_FOLLOW   => trans('seo.robots_type.noindex_follow.hint'),
        };
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->title(),
            'hint'  => $this->hint(),
        ];
    }
}
