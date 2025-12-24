<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

use App\Enums\SeoRobotsMetaEnum;
use App\Helpers\Utils;
use App\Models\Comment;
use App\Models\SeoOption;
use App\Models\UserView;
use App\Models\WishList;
use App\Services\Seo\InternalLinkingService;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

/**
 * DynamicSeo Livewire Component
 *
 * A comprehensive SEO management component for admin panel that allows
 * editing SEO settings, viewing analytics, and managing redirects.
 *
 * Features:
 * - SEO configuration (title, description, canonical, robots meta)
 * - Advanced SEO fields (OG image, Twitter image, focus keyword, author)
 * - SEO score calculation
 * - Focus keyword density tracking
 * - Social media previews (Google, Facebook, Twitter)
 * - Analytics charts (views, comments, wishes)
 * - 301 redirect management
 */
class DynamicSeo extends Component
{
    use Toast;
    use WithPagination;

    public string $tabSelected = 'config-tab';
    public mixed $model;
    public string $class;
    public string $back_route = '';
    public array $dates = [];

    // config
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

    // charts
    public array $viewsChart = [];
    public array $expandedComments = [];
    public $viewsChartSelectedMonth = 6;

    public array $commentsChart = [];
    public $commentsChartSelectedMonth = 6;

    public array $wishesChart = [];
    public $wishesChartSelectedMonth = 6;

    public function mount(string $class, int $id): void
    {
        // abort_if( ! config('custom-modules.seo'), 403);
        $this->class = $class;
        // Use eager loading to prevent N+1 queries
        $this->model = Utils::getEloquent($class)::with('seoOption')->find($id);
        $this->back_route = match ($class) {
            'courseSessionTemplate' => route('admin.course-session-template.index', ['courseTemplate' => $this->model->course_template_id]),
            default => route('admin.' . Str::kebab($class) . '.index'),
        };
        $this->slug = $this->model->slug;

        // Ensure seoOption exists, create if not
        if ( ! $this->model->seoOption) {
            $this->model->seoOption()->create([
                'title' => $this->model->title ?? '',
                'description' => $this->model->description ?? '',
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ]);
            $this->model->load('seoOption');
        }

        $this->seo_title = $this->model->seoOption->title ?? '';
        $this->seo_description = $this->model->seoOption->description ?? '';
        $this->canonical = $this->model->seoOption->canonical ?? '';
        $this->old_url = $this->model->seoOption->old_url ?? '';
        $this->redirect_to = $this->model->seoOption->redirect_to ?? '';
        $defaultRobotsMeta = SeoRobotsMetaEnum::INDEX_FOLLOW->value;
        $this->robots_meta = $this->model->seoOption->robots_meta?->value ?? $defaultRobotsMeta;
        $this->og_image = $this->model->seoOption->og_image ?? '';
        $this->twitter_image = $this->model->seoOption->twitter_image ?? '';
        $this->focus_keyword = $this->model->seoOption->focus_keyword ?? '';
        $this->meta_keywords = $this->model->seoOption->meta_keywords ?? '';
        $this->author = $this->model->seoOption->author ?? '';
        $this->sitemap_exclude = $this->model->seoOption->sitemap_exclude ?? false;
        $this->sitemap_priority = $this->model->seoOption->sitemap_priority ? (string) $this->model->seoOption->sitemap_priority : null;
        $this->sitemap_changefreq = $this->model->seoOption->sitemap_changefreq ?? null;
        $this->image_alt = $this->model->seoOption->image_alt ?? '';
        $this->image_title = $this->model->seoOption->image_title ?? '';

        $this->loadViewsChartData();
        $this->loadCommentsChartData();
        $this->loadWishesChartData();

        $this->dates = [
            ['value' => 1, 'label' => trans('seo.months.month_1'), 'end' => now(), 'start' => now()->subMonths()->startOfMonth()],
            ['value' => 3, 'label' => trans('seo.months.month_3'), 'end' => now(), 'start' => now()->subMonths(3)->startOfMonth()],
            ['value' => 6, 'label' => trans('seo.months.month_6'), 'end' => now(), 'start' => now()->subMonths(6)->startOfMonth()],
            ['value' => 12, 'label' => trans('seo.months.month_12'), 'end' => now(), 'start' => now()->subMonths(12)->startOfMonth()],
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

    private function loadViewsChartData(int $month = 3): void
    {
        // Cache chart data for 1 hour
        $modelClass = get_class($this->model);
        $cacheKey = "seo_views_chart_{$this->model->id}_{$modelClass}_{$month}";
        $this->viewsChart = Cache::remember($cacheKey, 3600, function () use ($month) {
            return $this->chartGenerator($this->baseViewsQuery(), 'bar', $month);
        });
    }

    private function loadCommentsChartData(int $month = 3): void
    {
        // Cache chart data for 1 hour
        $modelClass = get_class($this->model);
        $cacheKey = "seo_comments_chart_{$this->model->id}_{$modelClass}_{$month}";
        $this->commentsChart = Cache::remember($cacheKey, 3600, function () use ($month) {
            return $this->chartGenerator($this->baseCommentsQuery(), 'bar', $month);
        });
    }

    private function loadWishesChartData(int $month = 3): void
    {
        // Cache chart data for 1 hour
        $modelClass = get_class($this->model);
        $cacheKey = "seo_wishes_chart_{$this->model->id}_{$modelClass}_{$month}";
        $this->wishesChart = Cache::remember($cacheKey, 3600, function () use ($month) {
            return $this->chartGenerator($this->baseWishesQuery(), 'bar', $month);
        });
    }

    /**
     * @param string $chartType like bar|pie
     * @param int    $month     default 3
     */
    private function chartGenerator(Builder $baseQuery, string $chartType = 'bar', int $month = 3): array
    {
        $startDate = now()->subMonths($month)->startOfMonth();
        $data = $baseQuery
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

    private function countGenerator(Builder $baseQuery): array
    {
        $result = [];
        foreach ($this->dates as $date) {
            $result[$date['value']] = $baseQuery->clone()
                ->whereBetween('created_at', [$date['start'], $date['end']])
                ->count();
        }

        $result['all'] = $baseQuery->clone()->count();

        return $result;
    }

    protected function rules(): array
    {
        return [
            'slug' => [
                'required',
                'max:255',
                'unique:' . Str::plural($this->class) . ',slug,' . $this->model->id,
                function ($attribute, $value, $fail) {
                    if ($this->isSlugExistsInOtherModels($value)) {
                        $fail(trans('validation.unique', ['attribute' => trans('validation.attributes.slug')]));
                    }
                },
            ],
            'seo_title' => ['required', 'string', 'min:10', 'max:60'],
            'seo_description' => ['required', 'string', 'min:50', 'max:160'],
            'canonical' => ['nullable', 'max:255', 'url'],
            'old_url' => ['nullable', 'max:255', 'url'],
            'redirect_to' => ['nullable', 'max:255', 'url'],
            'robots_meta' => ['required', 'in:' . implode(',', SeoRobotsMetaEnum::values())],
            'og_image' => ['nullable', 'max:255', 'url'],
            'twitter_image' => ['nullable', 'max:255', 'url'],
            'focus_keyword' => ['nullable', 'string', 'max:100'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'author' => ['nullable', 'string', 'max:255'],
            'sitemap_exclude' => ['nullable', 'boolean'],
            'sitemap_priority' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'sitemap_changefreq' => ['nullable', 'string', 'in:always,hourly,daily,weekly,monthly,yearly,never'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'image_title' => ['nullable', 'string', 'max:255'],
        ];
    }

    /** Check if slug exists in other models with seoOption. */
    private function isSlugExistsInOtherModels(string $slug): bool
    {
        // List of models that use HasSeoOption trait and have slug field
        $modelsWithSeo = [
            'Blog' => \App\Models\Blog::class,
            'Event' => \App\Models\Event::class,
            'Bulletin' => \App\Models\Bulletin::class,
            'CourseTemplate' => \App\Models\CourseTemplate::class,
            'PortFolio' => \App\Models\PortFolio::class,
            'Page' => \App\Models\Page::class,
            'Category' => \App\Models\Category::class,
        ];

        foreach ($modelsWithSeo as $modelClass => $fullClass) {
            // Skip current model
            if ($modelClass === $this->class) {
                continue;
            }

            // Check if model has slug field and if slug exists
            if (method_exists($fullClass, 'where')) {
                $exists = $fullClass::where('slug', $slug)->where('id', '!=', $this->model->id)->exists();
                if ($exists) {
                    return true;
                }
            }
        }

        return false;
    }

    public function onSubmit(): void
    {
        $payload = $this->validate();

        // Ensure seoOption exists
        if ( ! $this->model->seoOption) {
            $this->model->seoOption()->create([
                'title' => $payload['seo_title'],
                'description' => $payload['seo_description'],
                'canonical' => $payload['canonical'],
                'old_url' => $payload['old_url'],
                'redirect_to' => $payload['redirect_to'],
                'robots_meta' => SeoRobotsMetaEnum::from($payload['robots_meta']),
                'og_image' => $payload['og_image'] ?? null,
                'twitter_image' => $payload['twitter_image'] ?? null,
                'focus_keyword' => $payload['focus_keyword'] ?? null,
                'meta_keywords' => $payload['meta_keywords'] ?? null,
                'author' => $payload['author'] ?? null,
                'sitemap_exclude' => $payload['sitemap_exclude'] ?? false,
                'sitemap_priority' => $payload['sitemap_priority'] ?? null,
                'sitemap_changefreq' => $payload['sitemap_changefreq'] ?? null,
                'image_alt' => $payload['image_alt'] ?? null,
                'image_title' => $payload['image_title'] ?? null,
            ]);
        } else {
            $this->model->seoOption->update([
                'title' => $payload['seo_title'],
                'description' => $payload['seo_description'],
                'canonical' => $payload['canonical'],
                'old_url' => $payload['old_url'],
                'redirect_to' => $payload['redirect_to'],
                'robots_meta' => SeoRobotsMetaEnum::from($payload['robots_meta']),
                'og_image' => $payload['og_image'] ?? null,
                'twitter_image' => $payload['twitter_image'] ?? null,
                'focus_keyword' => $payload['focus_keyword'] ?? null,
                'meta_keywords' => $payload['meta_keywords'] ?? null,
                'author' => $payload['author'] ?? null,
                'sitemap_exclude' => $payload['sitemap_exclude'] ?? false,
                'sitemap_priority' => $payload['sitemap_priority'] ?? null,
                'sitemap_changefreq' => $payload['sitemap_changefreq'] ?? null,
                'image_alt' => $payload['image_alt'] ?? null,
                'image_title' => $payload['image_title'] ?? null,
            ]);
        }

        $this->model->update([
            'slug' => $payload['slug'],
        ]);

        // Clear cache for seoOption attributes
        $this->clearSeoCache();

        $this->success(trans('general.update_success', ['model' => trans('seo.model')]));
    }

    /**
     * Calculate Flesch Reading Ease score for content.
     *
     * Score ranges:
     * - 90-100: Very Easy (5th grade)
     * - 80-89: Easy (6th grade)
     * - 70-79: Fairly Easy (7th grade)
     * - 60-69: Standard (8th-9th grade)
     * - 50-59: Fairly Difficult (10th-12th grade)
     * - 30-49: Difficult (College)
     * - 0-29: Very Difficult (College graduate)
     *
     * @return array{score: float, level: string, grade: string, suggestions: array}
     */
    public function calculateReadabilityScore(): array
    {
        $content = $this->model->description ?? $this->model->body ?? '';

        if (empty($content)) {
            return [
                'score' => 0,
                'level' => trans('seo.readability.no_content'),
                'grade' => '-',
                'suggestions' => [trans('seo.readability.add_content')],
            ];
        }

        // Remove HTML tags
        $text = strip_tags($content);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        if (empty($text)) {
            return [
                'score' => 0,
                'level' => trans('seo.readability.no_content'),
                'grade' => '-',
                'suggestions' => [trans('seo.readability.add_content')],
            ];
        }

        // Count sentences (ending with . ! ?)
        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $sentenceCount = count($sentences);

        if ($sentenceCount === 0) {
            $sentenceCount = 1;
        }

        // Count words
        $words = str_word_count($text);
        if ($words === 0) {
            return [
                'score' => 0,
                'level' => trans('seo.readability.no_content'),
                'grade' => '-',
                'suggestions' => [trans('seo.readability.add_content')],
            ];
        }

        // Count syllables (approximate)
        $syllables = $this->countSyllables($text);

        // Calculate Flesch Reading Ease
        // Score = 206.835 - (1.015 × ASL) - (84.6 × ASW)
        // ASL = Average Sentence Length = total words / total sentences
        // ASW = Average Syllables per Word = total syllables / total words
        $asl = $words / $sentenceCount;
        $asw = $syllables / $words;
        $score = 206.835 - (1.015 * $asl) - (84.6 * $asw);
        $score = max(0, min(100, round($score, 1))); // Clamp between 0-100

        // Determine level and grade
        $level = match (true) {
            $score >= 90 => trans('seo.readability.very_easy'),
            $score >= 80 => trans('seo.readability.easy'),
            $score >= 70 => trans('seo.readability.fairly_easy'),
            $score >= 60 => trans('seo.readability.standard'),
            $score >= 50 => trans('seo.readability.fairly_difficult'),
            $score >= 30 => trans('seo.readability.difficult'),
            default => trans('seo.readability.very_difficult'),
        };

        $grade = match (true) {
            $score >= 90 => '5th grade',
            $score >= 80 => '6th grade',
            $score >= 70 => '7th grade',
            $score >= 60 => '8th-9th grade',
            $score >= 50 => '10th-12th grade',
            $score >= 30 => 'College',
            default => 'College graduate',
        };

        // Generate suggestions
        $suggestions = $this->generateReadabilitySuggestions($score, $asl, $asw);

        return [
            'score' => $score,
            'level' => $level,
            'grade' => $grade,
            'stats' => [
                'words' => $words,
                'sentences' => $sentenceCount,
                'syllables' => $syllables,
                'avg_sentence_length' => round($asl, 1),
                'avg_syllables_per_word' => round($asw, 2),
            ],
            'suggestions' => $suggestions,
        ];
    }

    /** Count syllables in text (approximate). */
    protected function countSyllables(string $text): int
    {
        $words = str_word_count($text, 1);
        $syllableCount = 0;

        foreach ($words as $word) {
            $word = strtolower($word);
            $syllableCount += $this->countWordSyllables($word);
        }

        return $syllableCount;
    }

    /** Count syllables in a single word. */
    protected function countWordSyllables(string $word): int
    {
        $word = preg_replace('/[^a-z]/', '', strtolower($word));

        if (empty($word)) {
            return 0;
        }

        // Count vowel groups
        $vowels = preg_match_all('/[aeiouy]+/', $word);

        // Subtract silent e
        if (preg_match('/e$/', $word) && ! preg_match('/le$/', $word)) {
            $vowels--;
        }

        // Minimum 1 syllable
        return max(1, $vowels);
    }

    /** Generate readability improvement suggestions. */
    protected function generateReadabilitySuggestions(float $score, float $asl, float $asw): array
    {
        $suggestions = [];

        if ($score < 60) {
            if ($asl > 20) {
                $suggestions[] = trans('seo.readability.suggestion.short_sentences');
            }

            if ($asw > 1.8) {
                $suggestions[] = trans('seo.readability.suggestion.simple_words');
            }

            $suggestions[] = trans('seo.readability.suggestion.add_examples');
        } elseif ($score > 90) {
            $suggestions[] = trans('seo.readability.suggestion.more_technical');
        } else {
            $suggestions[] = trans('seo.readability.suggestion.good_level');
        }

        return $suggestions;
    }

    /**
     * Calculate focus keyword density in title, description, and content.
     * @return array{title: array, description: array, content: array, overall: float}
     */
    public function calculateFocusKeywordDensity(): array
    {
        if (empty($this->focus_keyword)) {
            return [
                'title' => ['count' => 0, 'density' => 0, 'found' => false],
                'description' => ['count' => 0, 'density' => 0, 'found' => false],
                'content' => ['count' => 0, 'density' => 0, 'found' => false],
                'overall' => 0,
            ];
        }

        $keyword = strtolower(trim($this->focus_keyword));
        $title = strtolower($this->seo_title ?? '');
        $description = strtolower($this->seo_description ?? '');
        $content = strtolower($this->model->description ?? $this->model->body ?? '');

        // Count occurrences
        $titleCount = substr_count($title, $keyword);
        $descriptionCount = substr_count($description, $keyword);
        $contentCount = substr_count($content, $keyword);

        // Calculate density (percentage)
        $titleWords = str_word_count($title);
        $descriptionWords = str_word_count($description);
        $contentWords = str_word_count($content);

        $titleDensity = $titleWords > 0 ? ($titleCount / $titleWords) * 100 : 0;
        $descriptionDensity = $descriptionWords > 0 ? ($descriptionCount / $descriptionWords) * 100 : 0;
        $contentDensity = $contentWords > 0 ? ($contentCount / $contentWords) * 100 : 0;

        // Overall density (weighted average)
        $overall = ($titleDensity * 0.3) + ($descriptionDensity * 0.3) + ($contentDensity * 0.4);

        return [
            'title' => [
                'count' => $titleCount,
                'density' => round($titleDensity, 2),
                'found' => $titleCount > 0,
            ],
            'description' => [
                'count' => $descriptionCount,
                'density' => round($descriptionDensity, 2),
                'found' => $descriptionCount > 0,
            ],
            'content' => [
                'count' => $contentCount,
                'density' => round($contentDensity, 2),
                'found' => $contentCount > 0,
            ],
            'overall' => round($overall, 2),
        ];
    }

    /**
     * Calculate SEO score based on various factors.
     * @return array{score: int, maxScore: int, details: array}
     */
    public function calculateSeoScore(): array
    {
        $score = 0;
        $maxScore = 100;
        $details = [];

        // Title length (20 points)
        $titleLength = strlen($this->seo_title ?? '');
        if ($titleLength >= 50 && $titleLength <= 60) {
            $score += 20;
            $details['title'] = ['status' => 'good', 'message' => trans('seo.score.title_good')];
        } elseif ($titleLength >= 40 && $titleLength < 50) {
            $score += 15;
            $details['title'] = ['status' => 'warning', 'message' => trans('seo.score.title_short')];
        } elseif ($titleLength > 60) {
            $score += 10;
            $details['title'] = ['status' => 'warning', 'message' => trans('seo.score.title_long')];
        } else {
            $details['title'] = ['status' => 'error', 'message' => trans('seo.score.title_invalid')];
        }

        // Description length (20 points)
        $descLength = strlen($this->seo_description ?? '');
        if ($descLength >= 150 && $descLength <= 160) {
            $score += 20;
            $details['description'] = ['status' => 'good', 'message' => trans('seo.score.description_good')];
        } elseif ($descLength >= 120 && $descLength < 150) {
            $score += 15;
            $details['description'] = ['status' => 'warning', 'message' => trans('seo.score.description_short')];
        } elseif ($descLength > 160) {
            $score += 10;
            $details['description'] = ['status' => 'warning', 'message' => trans('seo.score.description_long')];
        } else {
            $details['description'] = ['status' => 'error', 'message' => trans('seo.score.description_invalid')];
        }

        // Focus keyword (15 points)
        if ( ! empty($this->focus_keyword)) {
            $score += 15;
            $details['focus_keyword'] = ['status' => 'good', 'message' => trans('seo.score.focus_keyword_set')];
        } else {
            $details['focus_keyword'] = ['status' => 'warning', 'message' => trans('seo.score.focus_keyword_missing')];
        }

        // Focus keyword in title (10 points)
        if ( ! empty($this->focus_keyword) && stripos($this->seo_title ?? '', $this->focus_keyword) !== false) {
            $score += 10;
            $details['keyword_in_title'] = ['status' => 'good', 'message' => trans('seo.score.keyword_in_title')];
        } elseif ( ! empty($this->focus_keyword)) {
            $details['keyword_in_title'] = ['status' => 'warning', 'message' => trans('seo.score.keyword_not_in_title')];
        }

        // Focus keyword in description (10 points)
        if ( ! empty($this->focus_keyword) && stripos($this->seo_description ?? '', $this->focus_keyword) !== false) {
            $score += 10;
            $details['keyword_in_description'] = ['status' => 'good', 'message' => trans('seo.score.keyword_in_description')];
        } elseif ( ! empty($this->focus_keyword)) {
            $details['keyword_in_description'] = ['status' => 'warning', 'message' => trans('seo.score.keyword_not_in_description')];
        }

        // Canonical URL (10 points)
        if ( ! empty($this->canonical)) {
            $score += 10;
            $details['canonical'] = ['status' => 'good', 'message' => trans('seo.score.canonical_set')];
        } else {
            $details['canonical'] = ['status' => 'info', 'message' => trans('seo.score.canonical_optional')];
        }

        // OG Image (10 points)
        if ( ! empty($this->og_image)) {
            $score += 10;
            $details['og_image'] = ['status' => 'good', 'message' => trans('seo.score.og_image_set')];
        } else {
            $details['og_image'] = ['status' => 'warning', 'message' => trans('seo.score.og_image_missing')];
        }

        // Author (5 points)
        if ( ! empty($this->author)) {
            $score += 5;
            $details['author'] = ['status' => 'good', 'message' => trans('seo.score.author_set')];
        } else {
            $details['author'] = ['status' => 'info', 'message' => trans('seo.score.author_optional')];
        }

        return [
            'score' => $score,
            'maxScore' => $maxScore,
            'percentage' => round(($score / $maxScore) * 100),
            'details' => $details,
        ];
    }

    /** Clear SEO-related cache after update. */
    private function clearSeoCache(): void
    {
        $cacheKeys = [
            attributeCacheKey($this->model, 'seo_title'),
            attributeCacheKey($this->model, 'seo_description'),
            attributeCacheKey($this->model, 'seo_canonical'),
            attributeCacheKey($this->model, 'seo_redirect_to'),
            attributeCacheKey($this->model, 'seo_robot_meta'),
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    public function render(): View
    {
        // Cache count data for 30 minutes
        $modelClass = get_class($this->model);
        $cacheKey = "seo_counts_{$this->model->id}_{$modelClass}";
        $counts = Cache::remember($cacheKey, 1800, function () {
            return [
                'views' => $this->countGenerator($this->baseViewsQuery()),
                'comments' => $this->countGenerator($this->baseCommentsQuery()),
                'wishes' => $this->countGenerator($this->baseWishesQuery()),
            ];
        });

        return view('livewire.admin.shared.dynamic-seo', [
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => $this->back_route, 'label' => trans('general.page.index.title', ['model' => trans($this->class . '.model')])],
                ['label' => $this->model->title],
            ],
            'breadcrumbsActions' => [
                ['link' => $this->back_route, 'icon' => 's-arrow-left'],
            ],
            'viewsCount' => $counts['views'],
            'commentsCount' => $counts['comments'],
            'wishesCount' => $counts['wishes'],
            'seoScore' => $this->calculateSeoScore(),
            'keywordDensity' => $this->calculateFocusKeywordDensity(),
            'readabilityScore' => $this->calculateReadabilityScore(),
            'internalLinks' => (new InternalLinkingService)->getSuggestions($this->model, 10),

            'comments' => $this->baseCommentsQuery()->paginate(15),
            'views' => $this->baseViewsQuery()->paginate(15),
            'wishes' => $this->baseWishesQuery()->paginate(15),
        ]);
    }
}
