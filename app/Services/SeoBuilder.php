<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\Constants;
use App\Models\Blog;
use App\Models\PortFolio;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Database\Eloquent\Model;

class SeoBuilder
{
    protected ?string $title       = null;
    protected ?string $description = null;
    protected array $keywords      = [];
    protected array $images        = [];
    protected string $type         = 'article';
    protected ?string $url         = null;
    protected ?string $canonical   = null;
    protected array $hreflangs     = [];
    protected array $extraMeta     = [];

    protected bool $withOpenGraph   = true;
    protected bool $withJsonLd      = true;
    protected bool $withJsonLdMulti = true;

    protected ?Model $model = null;

    /**
     * Create a new SeoBuilder instance.
     *
     * @return static
     */
    public static function create(?Model $model = null): self
    {
        $instance            = new static;
        $instance->url       = request()->url();
        $instance->canonical = $instance->url;
        if ($model) {
            $instance->model = $model;
            $instance->applyModelDefaults($model);
        }

        return $instance;
    }

    /** Apply model-specific defaults. */
    protected function applyModelDefaults(Model $model): void
    {
        if ($model instanceof Blog) {
            $this->blog();
        } elseif ($model instanceof PortFolio) {
            $this->portfolio();
        }
    }

    /**
     * Set SEO for Blog model.
     * @return $this
     */
    public function blog(): self
    {
        $model      = $this->model;
        $this->type = 'article';
        $this->title ??= $model->title ?? null;
        $this->description ??= $model->description ?? $model->resume ?? null;
        $this->images   = [$model->getFirstMediaUrl('image', Constants::RESOLUTION_854_480)];
        $this->keywords = $this->keywords ?: ($model->keywords ?? []);

        return $this;
    }

    /**
     * Set SEO for Portfolio model.
     * @return $this
     */
    public function portfolio(): self
    {
        $model      = $this->model;
        $this->type = 'portfolio';
        $this->title ??= $model->title ?? null;
        $this->description ??= $model->description ?? null;
        $this->images = [$model->getFirstMediaUrl('image', Constants::RESOLUTION_720_SQUARE)];

        return $this;
    }

    /**
     * Set the title.
     * @return $this
     */
    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the description.
     * @return $this
     */
    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the keywords.
     * @return $this
     */
    public function keywords(array|string $keywords): self
    {
        $this->keywords = is_array($keywords) ? $keywords : array_map('trim', explode(',', $keywords));

        return $this;
    }

    /**
     * Set the images.
     * @return $this
     */
    public function images(array|string $images): self
    {
        $this->images = is_array($images) ? $images : [$images];

        return $this;
    }

    /**
     * Set the type.
     * @return $this
     */
    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set the URL.
     * @return $this
     */
    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set the canonical URL.
     * @return $this
     */
    public function canonical(string $canonical): self
    {
        $this->canonical = $canonical;

        return $this;
    }

    /**
     * Set hreflang alternate URLs.
     * @param  array $hreflangs [['lang' => 'en', 'url' => '...'], ...]
     * @return $this
     */
    public function hreflangs(array $hreflangs): self
    {
        $this->hreflangs = $hreflangs;

        return $this;
    }

    /**
     * Add a custom meta tag.
     * @return $this
     */
    public function addMeta(string $name, string $content, string $type = 'name'): self
    {
        $this->extraMeta[] = compact('name', 'content', 'type');

        return $this;
    }

    /**
     * Enable or disable OpenGraph tags.
     * @return $this
     */
    public function openGraph(bool $enable = true): self
    {
        $this->withOpenGraph = $enable;

        return $this;
    }

    /**
     * Enable or disable JsonLd tags.
     * @return $this
     */
    public function jsonLd(bool $enable = true): self
    {
        $this->withJsonLd = $enable;

        return $this;
    }

    /**
     * Enable or disable JsonLdMulti tags.
     * @return $this
     */
    public function jsonLdMulti(bool $enable = true): self
    {
        $this->withJsonLdMulti = $enable;

        return $this;
    }

    /**
     * Apply all SEO settings to the page.
     * @return $this
     */
    public function apply(): self
    {
        $this->applySeoMeta();
        $this->applyOpenGraph();
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
        if ($this->images) {
            foreach ($this->images as $image) {
                if ($image) {
                    OpenGraph::addImage($image);
                }
            }
        }
    }

    protected function applyJsonLd(): void
    {
        if ( ! $this->withJsonLd) {
            return;
        }
        JsonLd::setTitle($this->title);
        JsonLd::setDescription($this->description);
        JsonLd::setType($this->type);
        if ($this->images) {
            JsonLd::addImage($this->images);
        }
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
