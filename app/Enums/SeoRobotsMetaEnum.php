<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * SEO Robots Meta Enum
 *
 * Defines SEO robots meta tag options for search engine indexing.
 *
 * @OA\Schema(
 *     schema="SeoRobotsMetaEnum",
 *     @OA\Property(property="value", type="string", enum={"index_follow", "noindex_nofollow", "noindex_follow"}),
 *     @OA\Property(property="label", type="string"),
 * ),
 */
enum SeoRobotsMetaEnum: string
{
    use EnumToArray;

    case INDEX_FOLLOW     = 'index_follow';
    case NOINDEX_NOFOLLOW = 'noindex_nofollow';
    case NOINDEX_FOLLOW   = 'noindex_follow';

    public static function options(): array
    {
        return [
            [
                'label' => 'index,follow',
                'value' => self::INDEX_FOLLOW->value,
            ],
            [
                'label' => 'noindex,nofollow',
                'value' => self::NOINDEX_NOFOLLOW->value,
            ],
            [
                'label' => 'noindex,follow',
                'value' => self::NOINDEX_FOLLOW->value,
            ],
        ];
    }

    public function title(): string
    {
        return match ($this) {
            self::INDEX_FOLLOW     => 'index,follow',
            self::NOINDEX_NOFOLLOW => 'noindex,nofollow',
            self::NOINDEX_FOLLOW   => 'noindex,follow',
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
