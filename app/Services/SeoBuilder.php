<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\Constants;
use App\Models\Blog;
use App\Models\Bulletin;
use App\Models\CourseTemplate;
use App\Models\Event;
use App\Models\PortFolio;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Database\Eloquent\Model;

/**
 * SeoBuilder Service
 *
 * A comprehensive SEO service for generating meta tags, Open Graph tags,
 * Twitter Cards, and JSON-LD structured data for Laravel applications.
 *
 * @author Laravel Application
 * @since 1.0.0
 */
class SeoBuilder
{
    /** @var string|null SEO title for the page */
    protected ?string $title = null;

    /** @var string|null SEO description for the page */
    protected ?string $description = null;

    /** @var array<string> Keywords array for meta tags */
    protected array $keywords = [];

    /** @var array<string> Images array for Open Graph and Twitter Cards */
    protected array $images = [];

    /** @var string Content type (article, product, event, course, etc.) */
    protected string $type = 'article';

    /** @var string|null Current page URL */
    protected ?string $url = null;

    /** @var string|null Canonical URL */
    protected ?string $canonical = null;

    /** @var array<array{lang: string, url: string}> Hreflang alternate URLs */
    protected array $hreflangs = [];

    /** @var array<array{name: string, content: string, type: string}> Extra meta tags */
    protected array $extraMeta = [];

    /** @var bool Enable/disable Open Graph tags */
    protected bool $withOpenGraph = true;

    /** @var bool Enable/disable JSON-LD tags */
    protected bool $withJsonLd = true;

    /** @var bool Enable/disable JSON-LD Multi tags */
    protected bool $withJsonLdMulti = true;

    /** @var bool Enable/disable Twitter Card tags */
    protected bool $withTwitterCard = true;

    /** @var Model|null The model instance for SEO data */
    protected ?Model $model = null;

    /**
     * Create a new SeoBuilder instance.
     *
     * @param  Model|null $model Optional model instance to apply SEO defaults
     * @return static     New SeoBuilder instance
     */
    public static function create(?Model $model = null): self
    {
        $instance = new static;
        $instance->url = request()->url();
        $instance->canonical = $instance->url;
        if ($model) {
            $instance->model = $model;
            $instance->applyModelDefaults($model);
        }

        return $instance;
    }

    /**
     * Apply model-specific SEO defaults based on model type.
     *
     * @param Model $model The model instance
     */
    protected function applyModelDefaults(Model $model): void
    {
        if ($model instanceof Blog) {
            $this->blog();
        } elseif ($model instanceof PortFolio) {
            $this->portfolio();
        } elseif ($model instanceof Event) {
            $this->event();
        } elseif ($model instanceof Bulletin) {
            $this->news();
        } elseif ($model instanceof CourseTemplate) {
            $this->course();
        } else {
            $this->generic();
        }
    }

    /**
     * Set SEO configuration for Blog model.
     *
     * Configures title, description, canonical URL, images, and keywords
     * for blog posts. Uses seoOption if available, otherwise falls back
     * to model attributes.
     *
     * @return $this
     */
    public function blog(): self
    {
        $model = $this->model;
        $this->type = 'article';

        // Use seoOption if available, otherwise fallback to model attributes
        if (method_exists($model, 'seoOption') && $model->seoOption) {
            $this->title ??= $model->seoOption->title ?: $model->title;
            $this->description ??= $model->seoOption->description ?: $model->description;
            $this->canonical = $model->seoOption->canonical ?: $this->canonical;
        } else {
            $this->title ??= $model->title ?? null;
            $this->description ??= $model->description ?? null;
        }

        $this->images = [$model->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) ?: asset('assets/images/default/og-image.jpg')];
        $this->keywords = $this->keywords ?: ($model->keywords ?? []);

        $this->generateHreflangs();

        return $this;
    }

    /**
     * Set SEO for Portfolio model.
     * @return $this
     */
    public function portfolio(): self
    {
        $model = $this->model;
        $this->type = 'product';

        // Use seoOption if available
        if (method_exists($model, 'seoOption') && $model->seoOption) {
            $this->title ??= $model->seoOption->title ?: $model->title;
            $this->description ??= $model->seoOption->description ?: $model->description;
            $this->canonical = $model->seoOption->canonical ?: $this->canonical;
        } else {
            $this->title ??= $model->title ?? null;
            $this->description ??= $model->description ?? null;
        }

        $this->images = [$model->getFirstMediaUrl('image', Constants::RESOLUTION_720_SQUARE) ?: asset('assets/images/default/og-image.jpg')];

        $this->generateHreflangs();

        return $this;
    }

    /**
     * Set SEO for Event model.
     * @return $this
     */
    public function event(): self
    {
        $model = $this->model;
        $this->type = 'event';

        // Use seoOption if available
        if (method_exists($model, 'seoOption') && $model->seoOption) {
            $this->title ??= $model->seoOption->title ?: $model->title;
            $this->description ??= $model->seoOption->description ?: $model->description;
            $this->canonical = $model->seoOption->canonical ?: $this->canonical;
        } else {
            $this->title ??= $model->title ?? null;
            $this->description ??= $model->description ?? null;
        }

        $this->images = [$model->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) ?: asset('assets/images/default/og-image.jpg')];

        $this->generateHreflangs();

        return $this;
    }

    /**
     * Set SEO for News/Bulletin model.
     * @return $this
     */
    public function news(): self
    {
        $model = $this->model;
        $this->type = 'article';

        // Use seoOption if available
        if (method_exists($model, 'seoOption') && $model->seoOption) {
            $this->title ??= $model->seoOption->title ?: $model->title;
            $this->description ??= $model->seoOption->description ?: $model->description;
            $this->canonical = $model->seoOption->canonical ?: $this->canonical;
        } else {
            $this->title ??= $model->title ?? null;
            $this->description ??= $model->description ?? null;
        }

        $this->images = [$model->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) ?: asset('assets/images/default/og-image.jpg')];

        $this->generateHreflangs();

        return $this;
    }

    /**
     * Set SEO for Course model.
     * @return $this
     */
    public function course(): self
    {
        $model = $this->model;
        $this->type = 'course';

        // Use seoOption if available
        if (method_exists($model, 'seoOption') && $model->seoOption) {
            $this->title ??= $model->seoOption->title ?: $model->title;
            $this->description ??= $model->seoOption->description ?: $model->description;
            $this->canonical = $model->seoOption->canonical ?: $this->canonical;
        } else {
            $this->title ??= $model->title ?? null;
            $this->description ??= $model->description ?? null;
        }

        $this->images = [$model->getFirstMediaUrl('image', Constants::RESOLUTION_854_480) ?: asset('assets/images/default/og-image.jpg')];

        $this->generateHreflangs();

        return $this;
    }

    /**
     * Set SEO for generic model with seoOption.
     * @return $this
     */
    public function generic(): self
    {
        $model = $this->model;

        // Use seoOption if available
        if (method_exists($model, 'seoOption') && $model->seoOption) {
            $this->title ??= $model->seoOption->title ?: $model->title ?? null;
            $this->description ??= $model->seoOption->description ?: $model->description ?? null;
            $this->canonical = $model->seoOption->canonical ?: $this->canonical;
        } else {
            $this->title ??= $model->title ?? null;
            $this->description ??= $model->description ?? null;
        }

        // Try to get image if model has media
        if (method_exists($model, 'getFirstMediaUrl')) {
            $image = $model->getFirstMediaUrl('image', Constants::RESOLUTION_854_480);
            if ($image) {
                $this->images = [$image];
            }
        }

        $this->generateHreflangs();

        return $this;
    }

    /**
     * Generate hreflang tags based on model languages.
     *
     * Automatically creates hreflang alternate URLs for all supported
     * locales if the model has a languages property.
     */
    protected function generateHreflangs(): void
    {
        if ( ! $this->model || empty($this->hreflangs)) {
            // Auto-generate hreflangs if model has languages property
            if (property_exists($this->model, 'languages') && is_array($this->model->languages)) {
                $currentUrl = request()->url();
                $supportedLocales = config('app.supported_locales', ['fa', 'en']);

                foreach ($this->model->languages as $locale) {
                    if (in_array($locale, $supportedLocales, true)) {
                        // Generate URL for each locale
                        $url = str_replace('/' . app()->getLocale() . '/', '/' . $locale . '/', $currentUrl);
                        $this->hreflangs[] = [
                            'lang' => $locale,
                            'url' => $url,
                        ];
                    }
                }
            }
        }
    }

    /**
     * Set the SEO title.
     *
     * @param  string $title The page title (recommended: 50-60 characters)
     * @return $this
     */
    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the SEO description.
     *
     * @param  string $description The page description (recommended: 150-160 characters)
     * @return $this
     */
    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the SEO keywords.
     *
     * @param  array<string>|string $keywords Keywords as array or comma-separated string
     * @return $this
     */
    public function keywords(array|string $keywords): self
    {
        $this->keywords = is_array($keywords) ? $keywords : array_map('trim', explode(',', $keywords));

        return $this;
    }

    /**
     * Set images for Open Graph and Twitter Cards.
     *
     * @param  array<string>|string $images Image URLs as array or single string
     * @return $this
     */
    public function images(array|string $images): self
    {
        $this->images = is_array($images) ? $images : [$images];

        return $this;
    }

    /**
     * Set the content type for Open Graph and JSON-LD.
     *
     * Common types: article, product, event, course, website
     *
     * @param  string $type The content type
     * @return $this
     */
    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set the current page URL.
     *
     * @param  string $url The full URL of the page
     * @return $this
     */
    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set the canonical URL to prevent duplicate content issues.
     *
     * @param  string $canonical The canonical URL
     * @return $this
     */
    public function canonical(string $canonical): self
    {
        $this->canonical = $canonical;

        return $this;
    }

    /**
     * Set hreflang alternate URLs for multilingual content.
     *
     * @param  array<array{lang: string, url: string}> $hreflangs Array of language and URL pairs
     * @return $this
     */
    public function hreflangs(array $hreflangs): self
    {
        $this->hreflangs = $hreflangs;

        return $this;
    }

    /**
     * Add a custom meta tag.
     *
     * @param  string $name    The meta tag name
     * @param  string $content The meta tag content
     * @param  string $type    The meta tag type (name, property, etc.)
     * @return $this
     */
    public function addMeta(string $name, string $content, string $type = 'name'): self
    {
        $this->extraMeta[] = compact('name', 'content', 'type');

        return $this;
    }

    /**
     * Enable or disable Open Graph tags.
     *
     * @param  bool  $enable Enable (true) or disable (false) Open Graph tags
     * @return $this
     */
    public function openGraph(bool $enable = true): self
    {
        $this->withOpenGraph = $enable;

        return $this;
    }

    /**
     * Enable or disable JSON-LD structured data tags.
     *
     * @param  bool  $enable Enable (true) or disable (false) JSON-LD tags
     * @return $this
     */
    public function jsonLd(bool $enable = true): self
    {
        $this->withJsonLd = $enable;

        return $this;
    }

    /**
     * Enable or disable JSON-LD Multi tags.
     *
     * @param  bool  $enable Enable (true) or disable (false) JSON-LD Multi tags
     * @return $this
     */
    public function jsonLdMulti(bool $enable = true): self
    {
        $this->withJsonLdMulti = $enable;

        return $this;
    }

    /**
     * Enable or disable Twitter Card tags.
     *
     * @param  bool  $enable Enable (true) or disable (false) Twitter Card tags
     * @return $this
     */
    public function twitterCard(bool $enable = true): self
    {
        $this->withTwitterCard = $enable;

        return $this;
    }

    /**
     * Apply all SEO settings to the page.
     *
     * This method applies all configured SEO tags including:
     * - Meta tags (title, description, keywords, canonical, robots)
     * - Open Graph tags
     * - Twitter Card tags
     * - JSON-LD structured data
     *
     * @return $this
     */
    public function apply(): self
    {
        $this->applySeoMeta();
        $this->applyOpenGraph();
        $this->applyTwitterCard();
        $this->applyJsonLd();
        $this->applyJsonLdMulti();

        return $this;
    }

    protected function applySeoMeta(): void
    {
        SEOMeta::setTitle($this->title);
        SEOMeta::setDescription($this->description);
        if ($this->keywords) {
            SEOMeta::addKeyword($this->keywords);
        }
        foreach ($this->extraMeta as $meta) {
            SEOMeta::addMeta($meta['name'], $meta['content'], $meta['type']);
        }
        SEOMeta::setCanonical($this->canonical);

        // Apply robots meta from seoOption if available
        if ($this->model && method_exists($this->model, 'seoOption') && $this->model->seoOption) {
            $robotsMeta = $this->model->seoOption->robots_meta->value ?? 'index,follow';
            SEOMeta::setRobots($robotsMeta);
        }

        foreach ($this->hreflangs as $hreflang) {
            if (isset($hreflang['lang'], $hreflang['url'])) {
                SEOMeta::addAlternateLanguage($hreflang['lang'], $hreflang['url']);
            }
        }
    }

    protected function applyOpenGraph(): void
    {
        if ( ! $this->withOpenGraph) {
            return;
        }
        OpenGraph::setTitle($this->title);
        OpenGraph::setDescription($this->description);
        OpenGraph::setType($this->type);
        OpenGraph::setUrl($this->url);
        OpenGraph::setSiteName(config('seotools.opengraph.defaults.site_name', config('app.name')));
        OpenGraph::setLocale(app()->getLocale());

        // Use og_image from seoOption if available
        if ($this->model && method_exists($this->model, 'seoOption') && $this->model->seoOption && $this->model->seoOption->og_image) {
            OpenGraph::addImage($this->model->seoOption->og_image);
        } elseif ($this->images) {
            foreach ($this->images as $image) {
                if ($image) {
                    OpenGraph::addImage($image);
                }
            }
        } else {
            // Fallback to default image
            OpenGraph::addImage(asset('assets/images/default/og-image.jpg'));
        }
    }

    protected function applyTwitterCard(): void
    {
        if ( ! $this->withTwitterCard) {
            return;
        }

        // Determine Twitter Card type based on content
        $cardType = 'summary_large_image';
        if ($this->type === 'article' || $this->type === 'event') {
            $cardType = 'summary_large_image';
        } elseif ($this->type === 'product') {
            $cardType = 'summary';
        }

        TwitterCard::setType($cardType);
        TwitterCard::setTitle($this->title);
        TwitterCard::setDescription($this->description);
        TwitterCard::setUrl($this->url);

        // Set site from config if available
        $twitterSite = config('seotools.twitter.defaults.site');
        if ($twitterSite) {
            TwitterCard::setSite($twitterSite);
        }

        // Use twitter_image from seoOption if available
        if ($this->model && method_exists($this->model, 'seoOption') && $this->model->seoOption && $this->model->seoOption->twitter_image) {
            TwitterCard::addImage($this->model->seoOption->twitter_image);
        } elseif ($this->images) {
            foreach ($this->images as $image) {
                if ($image) {
                    TwitterCard::addImage($image);
                }
            }
        } else {
            // Fallback to default image
            TwitterCard::addImage(asset('assets/images/default/og-image.jpg'));
        }
    }

    protected function applyJsonLd(): void
    {
        if ( ! $this->withJsonLd) {
            return;
        }

        // Apply model-specific JSON-LD schemas
        if ($this->model) {
            $this->applyModelSpecificJsonLd();
        } else {
            // Generic JSON-LD
            JsonLd::setTitle($this->title);
            JsonLd::setDescription($this->description);
            JsonLd::setType('WebPage');
            if ($this->images) {
                JsonLd::addImage($this->images);
            }
        }
    }

    /** Apply model-specific JSON-LD schemas. */
    protected function applyModelSpecificJsonLd(): void
    {
        $model = $this->model;

        if ($model instanceof Blog || $model instanceof Bulletin) {
            $this->applyArticleJsonLd($model);
        } elseif ($model instanceof Event) {
            $this->applyEventJsonLd($model);
        } elseif ($model instanceof CourseTemplate) {
            $this->applyCourseJsonLd($model);
        } elseif ($model instanceof PortFolio) {
            $this->applyProductJsonLd($model);
        } else {
            // Generic WebPage schema
            JsonLd::setTitle($this->title);
            JsonLd::setDescription($this->description);
            JsonLd::setType('WebPage');
            JsonLd::setUrl($this->url);
            if ($this->images) {
                JsonLd::addImage($this->images);
            }
        }
    }

    /** Apply Article schema for Blog/News. */
    protected function applyArticleJsonLd($model): void
    {
        JsonLd::setType('Article');
        JsonLd::setTitle($this->title);
        JsonLd::setDescription($this->description);
        JsonLd::setUrl($this->url);

        if ($this->images) {
            JsonLd::addImage($this->images);
        }

        // Add article-specific properties
        if (method_exists($model, 'getFirstMediaUrl')) {
            $image = $model->getFirstMediaUrl('image', Constants::RESOLUTION_854_480);
            if ($image) {
                JsonLd::addValue('image', $image);
            }
        }

        if (property_exists($model, 'published_at') && $model->published_at) {
            JsonLd::addValue('datePublished', $model->published_at->toIso8601String());
        } elseif ($model->created_at) {
            JsonLd::addValue('datePublished', $model->created_at->toIso8601String());
        }

        if ($model->updated_at) {
            JsonLd::addValue('dateModified', $model->updated_at->toIso8601String());
        }

        // Add author if available (prefer seoOption author, then model user)
        $authorName = null;
        if (method_exists($model, 'seoOption') && $model->seoOption && $model->seoOption->author) {
            $authorName = $model->seoOption->author;
        } elseif (method_exists($model, 'user') && $model->user) {
            $authorName = $model->user->full_name ?? $model->user->name ?? null;
        }

        if ($authorName) {
            JsonLd::addValue('author', [
                '@type' => 'Person',
                'name' => $authorName,
            ]);
        }

        // Add publisher
        JsonLd::addValue('publisher', [
            '@type' => 'Organization',
            'name' => config('app.name'),
        ]);
    }

    /** Apply Event schema. */
    protected function applyEventJsonLd($model): void
    {
        JsonLd::setType('Event');
        JsonLd::setTitle($this->title);
        JsonLd::setDescription($this->description);
        JsonLd::setUrl($this->url);

        if ($this->images) {
            JsonLd::addImage($this->images);
        }

        // Add event-specific properties
        if (property_exists($model, 'start_date') && $model->start_date) {
            JsonLd::addValue('startDate', $model->start_date->toIso8601String());
        }

        if (property_exists($model, 'end_date') && $model->end_date) {
            JsonLd::addValue('endDate', $model->end_date->toIso8601String());
        }

        // Add location if available
        if (property_exists($model, 'location') && $model->location) {
            JsonLd::addValue('location', [
                '@type' => 'Place',
                'name' => $model->location,
            ]);
        }

        // Add organizer
        JsonLd::addValue('organizer', [
            '@type' => 'Organization',
            'name' => config('app.name'),
        ]);
    }

    /** Apply Course schema. */
    protected function applyCourseJsonLd($model): void
    {
        JsonLd::setType('Course');
        JsonLd::setTitle($this->title);
        JsonLd::setDescription($this->description);
        JsonLd::setUrl($this->url);

        if ($this->images) {
            JsonLd::addImage($this->images);
        }

        // Add course-specific properties
        JsonLd::addValue('provider', [
            '@type' => 'Organization',
            'name' => config('app.name'),
        ]);

        // Add course code if available
        if (property_exists($model, 'code') && $model->code) {
            JsonLd::addValue('courseCode', $model->code);
        }
    }

    /** Apply Product schema for Portfolio. */
    protected function applyProductJsonLd($model): void
    {
        JsonLd::setType('Product');
        JsonLd::setTitle($this->title);
        JsonLd::setDescription($this->description);
        JsonLd::setUrl($this->url);

        if ($this->images) {
            JsonLd::addImage($this->images);
        }

        // Add product-specific properties
        if (property_exists($model, 'price') && $model->price) {
            JsonLd::addValue('offers', [
                '@type' => 'Offer',
                'price' => $model->price,
                'priceCurrency' => 'IRR',
            ]);
        }

        // Add brand
        JsonLd::addValue('brand', [
            '@type' => 'Brand',
            'name' => config('app.name'),
        ]);
    }

    protected function applyJsonLdMulti(): void
    {
        if ( ! $this->withJsonLdMulti) {
            return;
        }
        JsonLdMulti::setTitle($this->title);
        JsonLdMulti::setDescription($this->description);
        JsonLdMulti::setType($this->type);
        if ($this->images) {
            JsonLdMulti::addImage($this->images);
        }
    }
}
