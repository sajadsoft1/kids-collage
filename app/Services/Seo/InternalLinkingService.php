<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Internal Linking Service
 *
 * Provides suggestions for internal linking based on content similarity,
 * keywords, categories, and tags.
 */
class InternalLinkingService
{
    /**
     * Get internal linking suggestions for a given model.
     *
     * @param  Model                                                                                                $model The model to get suggestions for
     * @param  int                                                                                                  $limit Maximum number of suggestions to return
     * @return array<array{model: Model, url: string, anchor_text: string, relevance_score: float, reason: string}>
     */
    public function getSuggestions(Model $model, int $limit = 5): array
    {
        $suggestions = [];

        // Get related content by category
        if (method_exists($model, 'category_id') && $model->category_id) {
            $categorySuggestions = $this->getByCategory($model, $limit);
            $suggestions = array_merge($suggestions, $categorySuggestions);
        }

        // Get related content by tags
        if (method_exists($model, 'tags') && $model->tags()->exists()) {
            $tagSuggestions = $this->getByTags($model, $limit);
            $suggestions = array_merge($suggestions, $tagSuggestions);
        }

        // Get related content by keywords
        if (method_exists($model, 'seoOption') && $model->seoOption && $model->seoOption->focus_keyword) {
            $keywordSuggestions = $this->getByKeywords($model, $limit);
            $suggestions = array_merge($suggestions, $keywordSuggestions);
        }

        // Get related content by title similarity
        $titleSuggestions = $this->getByTitleSimilarity($model, $limit);
        $suggestions = array_merge($suggestions, $titleSuggestions);

        // Remove duplicates and current model
        $suggestions = $this->deduplicateAndFilter($suggestions, $model);

        // Sort by relevance score
        usort($suggestions, fn ($a, $b) => $b['relevance_score'] <=> $a['relevance_score']);

        return array_slice($suggestions, 0, $limit);
    }

    /** Get suggestions based on category. */
    protected function getByCategory(Model $model, int $limit): array
    {
        $suggestions = [];
        $modelClass = get_class($model);

        $related = match ($modelClass) {
            Blog::class => Blog::where('category_id', $model->category_id)
                ->where('id', '!=', $model->id)
                ->where('published', 1)
                ->limit($limit * 2)
                ->get(),
            default => collect(),
        };

        foreach ($related as $item) {
            $suggestions[] = [
                'model' => $item,
                'url' => method_exists($item, 'path') ? $item->path() : '#',
                'anchor_text' => $this->generateAnchorText($item),
                'relevance_score' => 0.7,
                'reason' => trans('seo.linking.reason.category'),
            ];
        }

        return $suggestions;
    }

    /** Get suggestions based on tags. */
    protected function getByTags(Model $model, int $limit): array
    {
        $suggestions = [];

        if ( ! method_exists($model, 'tags')) {
            return $suggestions;
        }

        $tags = $model->tags()->pluck('name')->toArray();

        if (empty($tags)) {
            return $suggestions;
        }

        $modelClass = get_class($model);
        $related = match ($modelClass) {
            Blog::class => Blog::withAnyTags($tags)
                ->where('id', '!=', $model->id)
                ->where('published', 1)
                ->limit($limit * 2)
                ->get(),
            default => collect(),
        };

        foreach ($related as $item) {
            $suggestions[] = [
                'model' => $item,
                'url' => method_exists($item, 'path') ? $item->path() : '#',
                'anchor_text' => $this->generateAnchorText($item),
                'relevance_score' => 0.8,
                'reason' => trans('seo.linking.reason.tags'),
            ];
        }

        return $suggestions;
    }

    /** Get suggestions based on focus keyword. */
    protected function getByKeywords(Model $model, int $limit): array
    {
        $suggestions = [];

        if ( ! method_exists($model, 'seoOption') || ! $model->seoOption || ! $model->seoOption->focus_keyword) {
            return $suggestions;
        }

        $keyword = $model->seoOption->focus_keyword;
        $modelClass = get_class($model);

        $related = match ($modelClass) {
            Blog::class => Blog::whereHas('seoOption', function ($query) use ($keyword) {
                $query->where('focus_keyword', 'like', "%{$keyword}%");
            })
                ->where('id', '!=', $model->id)
                ->where('published', 1)
                ->limit($limit * 2)
                ->get(),
            default => collect(),
        };

        foreach ($related as $item) {
            $suggestions[] = [
                'model' => $item,
                'url' => method_exists($item, 'path') ? $item->path() : '#',
                'anchor_text' => $this->generateAnchorText($item),
                'relevance_score' => 0.9,
                'reason' => trans('seo.linking.reason.keyword'),
            ];
        }

        return $suggestions;
    }

    /** Get suggestions based on title similarity. */
    protected function getByTitleSimilarity(Model $model, int $limit): array
    {
        $suggestions = [];

        // Get title from model (works with HasTranslationAuto trait)
        $title = $model->title ?? null;

        if ( ! $title) {
            return $suggestions;
        }

        $titleWords = explode(' ', Str::lower($title));
        $titleWords = array_filter($titleWords, fn ($word) => strlen($word) > 3);
        $titleWords = array_slice($titleWords, 0, 3); // Use first 3 meaningful words

        if (empty($titleWords)) {
            return $suggestions;
        }

        $modelClass = get_class($model);
        $related = match ($modelClass) {
            Blog::class => Blog::whereHas('translationsPure', function ($query) use ($titleWords) {
                $query->where('key', 'title')
                    ->where('locale', app()->getLocale())
                    ->where(function ($q) use ($titleWords) {
                        foreach ($titleWords as $word) {
                            $q->orWhere('value', 'like', "%{$word}%");
                        }
                    });
            })
                ->where('id', '!=', $model->id)
                ->where('published', 1)
                ->limit($limit * 2)
                ->get(),
            default => collect(),
        };

        foreach ($related as $item) {
            $itemTitle = $item->title ?? '';
            $similarity = $this->calculateTitleSimilarity($title, $itemTitle);
            $suggestions[] = [
                'model' => $item,
                'url' => method_exists($item, 'path') ? $item->path() : '#',
                'anchor_text' => $this->generateAnchorText($item),
                'relevance_score' => $similarity * 0.6,
                'reason' => trans('seo.linking.reason.similarity'),
            ];
        }

        return $suggestions;
    }

    /** Generate anchor text for a model. */
    protected function generateAnchorText(Model $model): string
    {
        // Use focus keyword if available
        if (method_exists($model, 'seoOption') && $model->seoOption && $model->seoOption->focus_keyword) {
            return $model->seoOption->focus_keyword;
        }

        // Use title (first few words)
        if (isset($model->title)) {
            $words = explode(' ', $model->title);

            return implode(' ', array_slice($words, 0, 5));
        }

        return 'Read more';
    }

    /** Calculate title similarity between two strings. */
    protected function calculateTitleSimilarity(string $title1, string $title2): float
    {
        $words1 = explode(' ', Str::lower($title1));
        $words2 = explode(' ', Str::lower($title2));

        $commonWords = array_intersect($words1, $words2);
        $totalWords = count(array_unique(array_merge($words1, $words2)));

        return $totalWords > 0 ? count($commonWords) / $totalWords : 0;
    }

    /** Remove duplicates and filter out current model. */
    protected function deduplicateAndFilter(array $suggestions, Model $currentModel): array
    {
        $seen = [];
        $filtered = [];

        foreach ($suggestions as $suggestion) {
            $key = get_class($suggestion['model']) . '_' . $suggestion['model']->id;

            if ( ! isset($seen[$key]) && $suggestion['model']->id !== $currentModel->id) {
                $seen[$key] = true;
                $filtered[] = $suggestion;
            }
        }

        return $filtered;
    }
}
