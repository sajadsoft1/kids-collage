<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

use App\Enums\SeoRobotsMetaEnum;
use App\Livewire\Concerns\HandlesJsonSerialization;
use App\Services\Seo\DynamicSeoService;
use App\Services\Seo\InternalLinkingService;
use App\Services\Seo\SeoChartService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

/**
 * DynamicSeo Livewire Component
 *
 * A comprehensive SEO management component for admin panel.
 * Business logic is delegated to services for better maintainability.
 *
 * @see DynamicSeoService
 * @see SeoChartService
 */
class DynamicSeo extends Component
{
    use HandlesJsonSerialization;
    use Toast;
    use WithPagination;

    // ──────────────────────────────────────────────────────────────────────────
    // UI State
    // ──────────────────────────────────────────────────────────────────────────
    public string $tabSelected = 'config-tab';

    public array $expandedComments = [];

    public array $expandedViews = [];

    public array $expandedWishes = [];

    // ──────────────────────────────────────────────────────────────────────────
    // Model Identification (stored separately to avoid serialization issues)
    // ──────────────────────────────────────────────────────────────────────────
    #[Locked]
    public string $modelClass = '';

    #[Locked]
    public int $modelId = 0;

    public string $class;

    public string $back_route = '';

    // ──────────────────────────────────────────────────────────────────────────
    // SEO Form Fields
    // ──────────────────────────────────────────────────────────────────────────
    public ?string $slug = '';

    public ?string $seo_title = '';

    public ?string $seo_description = '';

    public ?string $canonical = '';

    public ?string $old_url = '';

    public ?string $redirect_to = '';

    public ?string $robots_meta = '';

    public ?string $og_image = '';

    public ?string $twitter_image = '';

    public ?string $focus_keyword = '';

    public ?string $meta_keywords = '';

    public ?string $author = '';

    public bool $sitemap_exclude = false;

    public ?string $sitemap_priority = null;

    public ?string $sitemap_changefreq = null;

    public ?string $image_alt = '';

    public ?string $image_title = '';

    // ──────────────────────────────────────────────────────────────────────────
    // Chart Selection State
    // ──────────────────────────────────────────────────────────────────────────
    public int $viewsChartSelectedMonth = 6;

    public int $commentsChartSelectedMonth = 6;

    public int $wishesChartSelectedMonth = 6;

    // ──────────────────────────────────────────────────────────────────────────
    // Service Instance (not public to avoid serialization)
    // ──────────────────────────────────────────────────────────────────────────
    protected ?DynamicSeoService $seoService = null;

    protected ?Model $cachedModel = null;

    /** Get the model instance (lazy loaded and cached for the request). */
    #[Computed]
    public function model(): Model
    {
        if ($this->cachedModel === null) {
            $modelClass = $this->modelClass;
            $this->cachedModel = $modelClass::with('seoOption')->find($this->modelId);
        }

        return $this->cachedModel;
    }

    /** Get or create the SEO service instance. */
    protected function getSeoService(): DynamicSeoService
    {
        if ($this->seoService === null) {
            $this->seoService = new DynamicSeoService($this->model);
        }

        return $this->seoService;
    }

    // ══════════════════════════════════════════════════════════════════════════
    // LIFECYCLE
    // ══════════════════════════════════════════════════════════════════════════

    public function mount(string $class, int $id): void
    {
        // Use the service to load and validate the model
        $service = DynamicSeoService::make($class, $id);

        // Store identifiers for later use (not the model itself to avoid serialization)
        $this->class = $class;
        $this->modelId = $id;
        $this->modelClass = $service->getModel()::class;
        $this->seoService = $service;
        $this->cachedModel = $service->getModel();

        $service->ensureSeoOptionExists();
        $this->cachedModel = null; // Reset cache to reload with seoOption

        $this->back_route = $service->getBackRoute($class);

        // Initialize form fields from service
        $formData = $service->getSeoFormData();
        $this->slug = $formData['slug'];
        $this->seo_title = $formData['seo_title'];
        $this->seo_description = $formData['seo_description'];
        $this->canonical = $formData['canonical'];
        $this->old_url = $formData['old_url'];
        $this->redirect_to = $formData['redirect_to'];
        $this->robots_meta = $formData['robots_meta'];
        $this->og_image = $formData['og_image'];
        $this->twitter_image = $formData['twitter_image'];
        $this->focus_keyword = $formData['focus_keyword'];
        $this->meta_keywords = $formData['meta_keywords'];
        $this->author = $formData['author'];
        $this->sitemap_exclude = $formData['sitemap_exclude'];
        $this->sitemap_priority = $formData['sitemap_priority'];
        $this->sitemap_changefreq = $formData['sitemap_changefreq'];
        $this->image_alt = $formData['image_alt'];
        $this->image_title = $formData['image_title'];
    }

    // ══════════════════════════════════════════════════════════════════════════
    // COMPUTED PROPERTIES (No serialization issues)
    // ══════════════════════════════════════════════════════════════════════════

    /** Get date options for select dropdowns (serializable). */
    #[Computed]
    public function dates(): array
    {
        return SeoChartService::getDateOptions();
    }

    /** Get views chart data. */
    #[Computed]
    public function viewsChart(): array
    {
        return $this->getSeoService()->charts()->getViewsChartData($this->viewsChartSelectedMonth);
    }

    /** Get comments chart data. */
    #[Computed]
    public function commentsChart(): array
    {
        return $this->getSeoService()->charts()->getCommentsChartData($this->commentsChartSelectedMonth);
    }

    /** Get wishes chart data. */
    #[Computed]
    public function wishesChart(): array
    {
        return $this->getSeoService()->charts()->getWishesChartData($this->wishesChartSelectedMonth);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // ACTIONS
    // ══════════════════════════════════════════════════════════════════════════

    public function onSubmit(): void
    {
        $payload = $this->validate();

        $this->getSeoService()->update($payload);

        $this->success(trans('general.update_success', ['model' => trans('seo.model')]));
    }

    // ══════════════════════════════════════════════════════════════════════════
    // VALIDATION
    // ══════════════════════════════════════════════════════════════════════════

    protected function rules(): array
    {
        return $this->getSeoService()->getValidationRules($this->class);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // RENDER
    // ══════════════════════════════════════════════════════════════════════════

    public function render(): View
    {
        $service = $this->getSeoService();
        $counts = $service->charts()->getAllCounts();

        return view('livewire.admin.shared.dynamic-seo', [
            // Navigation
            'breadcrumbs' => $service->getBreadcrumbs($this->back_route, $this->class),
            'breadcrumbsActions' => $service->getBreadcrumbActions($this->back_route),

            // Statistics
            'viewsCount' => $counts['views'],
            'commentsCount' => $counts['comments'],
            'wishesCount' => $counts['wishes'],

            // SEO Analysis
            'seoScore' => $service->scores()->calculateSeoScore(),
            'keywordDensity' => $service->scores()->calculateKeywordDensity(),
            'readabilityScore' => $service->scores()->calculateReadabilityScore(),
            'internalLinks' => (new InternalLinkingService)->getSuggestions($this->model, 10),

            // Date ranges for stats display
            'dateRanges' => SeoChartService::getDateRanges(),

            // Paginated data
            'comments' => $service->charts()->getCommentsPaginated(15),
            'views' => $service->charts()->getViewsPaginated(15),
            'wishes' => $service->charts()->getWishesPaginated(15),

            // Enum for robots meta select
            'robotsMetaOptions' => SeoRobotsMetaEnum::cases(),
        ]);
    }
}
