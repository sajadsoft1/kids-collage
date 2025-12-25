<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Models\SeoOption;
use Illuminate\Database\Eloquent\Model;

/**
 * SEO Score Service
 *
 * Calculates SEO scores, readability metrics, and keyword density
 * for content optimization.
 */
class SeoScoreService
{
    protected Model $model;

    protected ?SeoOption $seoOption;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->seoOption = $model->seoOption ?? null;
    }

    /**
     * Calculate SEO score based on various factors.
     *
     * @return array{score: int, maxScore: int, percentage: int, details: array}
     */
    public function calculateSeoScore(): array
    {
        $score = 0;
        $maxScore = 100;
        $details = [];

        $title = $this->seoOption?->title ?? '';
        $description = $this->seoOption?->description ?? '';
        $focusKeyword = $this->seoOption?->focus_keyword ?? '';
        $canonical = $this->seoOption?->canonical ?? '';
        $ogImage = $this->seoOption?->og_image ?? '';
        $author = $this->seoOption?->author ?? '';

        // Title length (20 points)
        $titleLength = strlen($title);
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
        $descLength = strlen($description);
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
        if ( ! empty($focusKeyword)) {
            $score += 15;
            $details['focus_keyword'] = ['status' => 'good', 'message' => trans('seo.score.focus_keyword_set')];
        } else {
            $details['focus_keyword'] = ['status' => 'warning', 'message' => trans('seo.score.focus_keyword_missing')];
        }

        // Focus keyword in title (10 points)
        if ( ! empty($focusKeyword) && stripos($title, $focusKeyword) !== false) {
            $score += 10;
            $details['keyword_in_title'] = ['status' => 'good', 'message' => trans('seo.score.keyword_in_title')];
        } elseif ( ! empty($focusKeyword)) {
            $details['keyword_in_title'] = ['status' => 'warning', 'message' => trans('seo.score.keyword_not_in_title')];
        }

        // Focus keyword in description (10 points)
        if ( ! empty($focusKeyword) && stripos($description, $focusKeyword) !== false) {
            $score += 10;
            $details['keyword_in_description'] = ['status' => 'good', 'message' => trans('seo.score.keyword_in_description')];
        } elseif ( ! empty($focusKeyword)) {
            $details['keyword_in_description'] = ['status' => 'warning', 'message' => trans('seo.score.keyword_not_in_description')];
        }

        // Canonical URL (10 points)
        if ( ! empty($canonical)) {
            $score += 10;
            $details['canonical'] = ['status' => 'good', 'message' => trans('seo.score.canonical_set')];
        } else {
            $details['canonical'] = ['status' => 'info', 'message' => trans('seo.score.canonical_optional')];
        }

        // OG Image (10 points)
        if ( ! empty($ogImage)) {
            $score += 10;
            $details['og_image'] = ['status' => 'good', 'message' => trans('seo.score.og_image_set')];
        } else {
            $details['og_image'] = ['status' => 'warning', 'message' => trans('seo.score.og_image_missing')];
        }

        // Author (5 points)
        if ( ! empty($author)) {
            $score += 5;
            $details['author'] = ['status' => 'good', 'message' => trans('seo.score.author_set')];
        } else {
            $details['author'] = ['status' => 'info', 'message' => trans('seo.score.author_optional')];
        }

        return [
            'score' => $score,
            'maxScore' => $maxScore,
            'percentage' => (int) round(($score / $maxScore) * 100),
            'details' => $details,
        ];
    }

    /**
     * Calculate focus keyword density in title, description, and content.
     *
     * @return array{title: array, description: array, content: array, overall: float}
     */
    public function calculateKeywordDensity(): array
    {
        $focusKeyword = $this->seoOption?->focus_keyword ?? '';

        if (empty($focusKeyword)) {
            return [
                'title' => ['count' => 0, 'density' => 0, 'found' => false],
                'description' => ['count' => 0, 'density' => 0, 'found' => false],
                'content' => ['count' => 0, 'density' => 0, 'found' => false],
                'overall' => 0,
            ];
        }

        $keyword = strtolower(trim($focusKeyword));
        $title = strtolower($this->seoOption?->title ?? '');
        $description = strtolower($this->seoOption?->description ?? '');
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
     * @return array{score: float, level: string, grade: string, stats: array, suggestions: array}
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
        $asl = $words / $sentenceCount;
        $asw = $syllables / $words;
        $score = 206.835 - (1.015 * $asl) - (84.6 * $asw);
        $score = max(0, min(100, round($score, 1)));

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
}
