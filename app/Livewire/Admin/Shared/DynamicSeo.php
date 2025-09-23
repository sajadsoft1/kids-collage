<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

use App\Enums\SeoRobotsMetaEnum;
use App\Helpers\Utils;
use App\Models\Comment;
use App\Models\UserView;
use App\Models\WishList;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class DynamicSeo extends Component
{
    use WithPagination;

    public string $tabSelected = 'view-tab';
    public mixed $model;
    public string $class;
    public string $back_route  = '';
    public array $dates        = [];

    // config
    public ?string $slug            = '';
    public ?string $seo_title       = '';
    public ?string $seo_description = '';
    public ?string $canonical       = '';
    public ?string $old_url         = '';
    public ?string $redirect_to     = '';
    public ?string $robots_meta     = SeoRobotsMetaEnum::INDEX_FOLLOW->value;

    // charts
    public array $viewsChart              = [];
    public $viewsChartSelectedMonth       = 6;

    public array $commentsChart              = [];
    public $commentsChartSelectedMonth       = 6;

    public array $wishesChart              = [];
    public $wishesChartSelectedMonth       = 6;

    public array $likesChart              = [];
    public $likesChartSelectedMonth       = 6;

    public function mount(string $class, int $id): void
    {
        $this->class           = $class;
        $this->back_route      = 'admin.' . Str::kebab($class) . '.index';
        $this->model           = Utils::getEloquent($class)::find($id);
        $this->slug            = $this->model->slug;
        $this->seo_title       = $this->model->seoOption->title;
        $this->seo_description = $this->model->seoOption->description;
        $this->canonical       = $this->model->seoOption->canonical;
        $this->old_url         = $this->model->seoOption->old_url;
        $this->redirect_to     = $this->model->seoOption->redirect_to;
        $this->robots_meta     = $this->model->seoOption->robots_meta->value;

        $this->loadViewsChartData();
        $this->loadCommentsChartData();
        $this->loadLikesChartData();

        $this->dates = [
            ['value' => 1, 'label' => trans('seo.months.month_1')],
            ['value' => 3, 'label' => trans('seo.months.month_3')],
            ['value' => 6, 'label' => trans('seo.months.month_6')],
            ['value' => 12, 'label' => trans('seo.months.month_12')],
        ];
    }

    protected function baseViewsQuery(): Builder
    {
        return UserView::query()
            ->where('morphable_type', $this->model::class)
            ->where('morphable_id', $this->model->id);
    }

    protected function baseCommentsQuery(): Builder
    {
        return Comment::query()
            ->where('morphable_type', $this->model::class)
            ->where('morphable_id', $this->model->id);
    }

    protected function baseWishesQuery(): Builder
    {
        return WishList::query()
            ->where('morphable_type', $this->model::class)
            ->where('morphable_id', $this->model->id);
    }

    public function updatedViewsChartSelectedMonth($value): void
    {
        $this->loadViewsChartData((int) $value);
    }

    public function updatedCommentsChartSelectedMonth($value): void
    {
        $this->loadCommentsChartData((int) $value);
    }

    public function updatedWishesChartSelectedMonth($value): void
    {
        $this->loadWishesChartData((int) $value);
    }

    public function updatedLikesChartSelectedMonth($value): void
    {
        $this->loadLikesChartData((int) $value);
    }

    private function loadViewsChartData(int $month = 3): void
    {
        $startDate = now()->subMonths($month)->startOfMonth();
        $views     = $this->baseViewsQuery()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data   = [];
        foreach (CarbonPeriod::create($startDate, '1 month', now()->startOfMonth()) as $date) {
            $month    = $date->format('Y-m');
            $labels[] = $month;
            $data[]   = $views->firstWhere('month', $month)?->count ?? 0;
        }

        $this->viewsChart = [
            'type' => 'bar',
            'data' => [
                'labels'   => $labels,
                'datasets' => [
                    [
                        'label' => '# of Views',
                        'data'  => $data,
                    ],
                ],
            ],
        ];
    }

    private function loadCommentsChartData(int $month = 3): void
    {
        $startDate = now()->subMonths($month)->startOfMonth();
        $comments  = $this->baseCommentsQuery()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data   = [];
        foreach (CarbonPeriod::create($startDate, '1 month', now()->startOfMonth()) as $date) {
            $monthLabel = $date->format('Y-m');
            $labels[]   = $monthLabel;
            $data[]     = $comments->firstWhere('month', $monthLabel)?->count ?? 0;
        }

        $this->commentsChart = [
            'type' => 'bar',
            'data' => [
                'labels'   => $labels,
                'datasets' => [
                    [
                        'label' => '# of Comments',
                        'data'  => $data,
                    ],
                ],
            ],
        ];
    }

    private function loadWishesChartData(int $month = 3): void {}

    private function loadLikesChartData(int $month = 3): void
    {
        $startDate = now()->subMonths($month)->startOfMonth();
        $views     = $this->baseWishesQuery()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data   = [];
        foreach (CarbonPeriod::create($startDate, '1 month', now()->startOfMonth()) as $date) {
            $month    = $date->format('Y-m');
            $labels[] = $month;
            $data[]   = $views->firstWhere('month', $month)?->count ?? 0;
        }

        $this->likesChart = [
            'type' => 'bar',
            'data' => [
                'labels'   => $labels,
                'datasets' => [
                    [
                        'label' => '# of Likes',
                        'data'  => $data,
                    ],
                ],
            ],
        ];
    }

    protected function rules(): array
    {
        return [
            'slug'            => ['required', 'max:255', 'unique:' . Str::plural($this->class) . ',slug,' . $this->model->id],
            'seo_title'       => ['required', 'max:255'],
            'seo_description' => ['required', 'max:500'],
            'canonical'       => ['nullable', 'max:255', 'url'],
            'old_url'         => ['nullable', 'max:255', 'url'],
            'redirect_to'     => ['nullable', 'max:255', 'url'],
            'robots_meta'     => ['required', 'in:' . implode(',', SeoRobotsMetaEnum::values())],
        ];
    }

    public function onSubmit(): void
    {
       $payload =  $this->validate();

      $this->model->seoOption->update([
          'title'       => $payload['seo_title'],
          'description' => $payload['seo_description'],
          'canonical'   => $payload['canonical'],
          'old_url'     => $payload['old_url'],
          'redirect_to' => $payload['redirect_to'],
          'robots_meta' => SeoRobotsMetaEnum::from($payload['robots_meta']),
      ]);

      $this->model->update([
          'slug' => $payload['slug'],
      ]);

    }

    public function render(): View
    {
        return view('livewire.admin.shared.dynamic-seo', [
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.' . $this->class . '.index'), 'label' => trans('general.page.index.title', ['model' => trans($this->class . '.model')])],
                ['label' => $this->model->title],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.' . $this->class . '.index'), 'icon' => 's-arrow-left'],
            ],
            'viewsCount'         => $this->baseViewsQuery()->count(),
            'commentsCount'      => $this->baseCommentsQuery()->count(),
            'likesCount'         => $this->baseWishesQuery()->count(),
            'wishesCount'        => 0,

            'comments'           => $this->baseCommentsQuery()->paginate(15),
            'views'              => $this->baseViewsQuery()->paginate(15),
            'likes'              => $this->baseWishesQuery()->paginate(15),
        ]);
    }
}
