<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SeoRobotsMetaEnum;
use App\Traits\MorphAttributesTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 */
class SeoOption extends Model
{
    use MorphAttributesTrait;

    protected $fillable = [
        'morphable_type', 'morphable_id', 'title', 'description', 'canonical', 'old_url', 'redirect_to', 'robots_meta',
        'og_image', 'twitter_image', 'focus_keyword', 'meta_keywords', 'author',
        'sitemap_exclude', 'sitemap_priority', 'sitemap_changefreq',
        'image_alt', 'image_title',
    ];

    protected $casts = [
        'robots_meta' => SeoRobotsMetaEnum::class,
        'sitemap_exclude' => 'boolean',
        'sitemap_priority' => 'decimal:1',
    ];

    /**
     * Model Configuration --------------------------------------------------------------------------
     */

    /**
     * Model Relations --------------------------------------------------------------------------
     */

    /**
     * Model Scope --------------------------------------------------------------------------
     */

    /**
     * Model Attributes --------------------------------------------------------------------------
     */

    /**
     * Model Custom Methods --------------------------------------------------------------------------
     */
}
