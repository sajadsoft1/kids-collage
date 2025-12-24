<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Models\Comment;
use App\Models\UserView;
use App\Models\WishList;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * SEO Chart Service
 *
 * Handles chart data generation and statistics for SEO analytics.
 * Provides cached data for views, comments, and wishes charts.
 */
class SeoChartService
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get chart data for views.
     *
     * @param int    $months    Number of months to show
     * @param string $chartType Chart type (bar, line, etc.)
     */
    public function getViewsChartData(int $months = 3, string $chartType = 'bar'): array
    {
        $cacheKey = $this->getCacheKey('views_chart', $months);

        return Cache::remember($cacheKey, 3600, function () use ($months, $chartType) {
            return $this->generateChartData($this->getViewsQuery(), $chartType, $months);
        });
    }

    /**
     * Get chart data for comments.
     *
     * @param int    $months    Number of months to show
     * @param string $chartType Chart type (bar, line, etc.)
     */
    public function getCommentsChartData(int $months = 3, string $chartType = 'bar'): array
    {
        $cacheKey = $this->getCacheKey('comments_chart', $months);

        return Cache::remember($cacheKey, 3600, function () use ($months, $chartType) {
            return $this->generateChartData($this->getCommentsQuery(), $chartType, $months);
        });
    }

    /**
     * Get chart data for wishes.
     *
     * @param int    $months    Number of months to show
     * @param string $chartType Chart type (bar, line, etc.)
     */
    public function getWishesChartData(int $months = 3, string $chartType = 'bar'): array
    {
        $cacheKey = $this->getCacheKey('wishes_chart', $months);

        return Cache::remember($cacheKey, 3600, function () use ($months, $chartType) {
            return $this->generateChartData($this->getWishesQuery(), $chartType, $months);
        });
    }

    /**
     * Get counts for all date ranges.
     *
     * @return array{views: array, comments: array, wishes: array}
     */
    public function getAllCounts(): array
    {
        $cacheKey = $this->getCacheKey('counts');

        return Cache::remember($cacheKey, 1800, function () {
            return [
                'views' => $this->generateCounts($this->getViewsQuery()),
                'comments' => $this->generateCounts($this->getCommentsQuery()),
                'wishes' => $this->generateCounts($this->getWishesQuery()),
            ];
        });
    }

    /**
     * Get paginated views data.
     *
     * @param int $perPage Items per page
     */
    public function getViewsPaginated(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->getViewsQuery()->paginate($perPage);
    }

    /**
     * Get paginated comments data.
     *
     * @param int $perPage Items per page
     */
    public function getCommentsPaginated(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->getCommentsQuery()->paginate($perPage);
    }

    /**
     * Get paginated wishes data.
     *
     * @param int $perPage Items per page
     */
    public function getWishesPaginated(int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->getWishesQuery()->paginate($perPage);
    }

    /**
     * Get date ranges for select options (serializable).
     *
     * @return array<array{value: int, label: string}>
     */
    public static function getDateOptions(): array
    {
        return [
            ['value' => 1, 'label' => trans('seo.months.month_1')],
            ['value' => 3, 'label' => trans('seo.months.month_3')],
            ['value' => 6, 'label' => trans('seo.months.month_6')],
            ['value' => 12, 'label' => trans('seo.months.month_12')],
        ];
    }

    /**
     * Get date ranges with Carbon objects (for calculations).
     *
     * @return array<array{value: int, label: string, start: \Carbon\Carbon, end: \Carbon\Carbon}>
     */
    public static function getDateRanges(): array
    {
        return [
            ['value' => 1, 'label' => trans('seo.months.month_1'), 'end' => now(), 'start' => now()->subMonths()->startOfMonth()],
            ['value' => 3, 'label' => trans('seo.months.month_3'), 'end' => now(), 'start' => now()->subMonths(3)->startOfMonth()],
            ['value' => 6, 'label' => trans('seo.months.month_6'), 'end' => now(), 'start' => now()->subMonths(6)->startOfMonth()],
            ['value' => 12, 'label' => trans('seo.months.month_12'), 'end' => now(), 'start' => now()->subMonths(12)->startOfMonth()],
        ];
    }

    /** Clear all cached chart data for this model. */
    public function clearCache(): void
    {
        $types = ['views_chart', 'comments_chart', 'wishes_chart', 'counts'];
        $months = [1, 3, 6, 12];

        foreach ($types as $type) {
            if ($type === 'counts') {
                Cache::forget($this->getCacheKey($type));
            } else {
                foreach ($months as $month) {
                    Cache::forget($this->getCacheKey($type, $month));
                }
            }
        }
    }

    /** Generate cache key for chart data. */
    protected function getCacheKey(string $type, ?int $months = null): string
    {
        $modelClass = get_class($this->model);
        $key = "seo_{$type}_{$this->model->id}_{$modelClass}";

        if ($months !== null) {
            $key .= "_{$months}";
        }

        return $key;
    }

    /** Get base query for views. */
    protected function getViewsQuery(): Builder
    {
        return UserView::query()
            ->where('morphable_type', $this->model::class)
            ->where('morphable_id', $this->model->id);
    }

    /** Get base query for comments. */
    protected function getCommentsQuery(): Builder
    {
        return Comment::query()
            ->where('morphable_type', $this->model::class)
            ->where('morphable_id', $this->model->id);
    }

    /** Get base query for wishes. */
    protected function getWishesQuery(): Builder
    {
        return WishList::query()
            ->where('morphable_type', $this->model::class)
            ->where('morphable_id', $this->model->id);
    }

    /**
     * Generate chart data from a query.
     *
     * @param Builder $query     Base query
     * @param string  $chartType Chart type (bar, line, etc.)
     * @param int     $months    Number of months
     */
    protected function generateChartData(Builder $query, string $chartType, int $months): array
    {
        $startDate = now()->subMonths($months)->startOfMonth();

        $data = $query
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $counts = [];

        foreach (CarbonPeriod::create($startDate, '1 month', now()->startOfMonth()) as $date) {
            $monthLabel = $date->format('Y-m');
            $labels[] = $monthLabel;
            $counts[] = $data->firstWhere('month', $monthLabel)?->count ?? 0;
        }

        return [
            'type' => $chartType,
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => '# of Items',
                        'data' => $counts,
                    ],
                ],
            ],
        ];
    }

    /**
     * Generate counts for all date ranges.
     *
     * @param Builder $query Base query
     *
     * @return array<int|string, int>
     */
    protected function generateCounts(Builder $query): array
    {
        $result = [];
        $dateRanges = self::getDateRanges();

        foreach ($dateRanges as $date) {
            $result[$date['value']] = $query->clone()
                ->whereBetween('created_at', [$date['start'], $date['end']])
                ->count();
        }

        $result['all'] = $query->clone()->count();

        return $result;
    }
}
