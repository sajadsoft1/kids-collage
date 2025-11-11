<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DifficultyEnum;
use App\Enums\QuestionTypeEnum;
use App\QuestionTypes\AbstractQuestionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Tags\HasTags;

class Question extends Model
{
    use HasFactory,HasTags;

    protected $fillable = [
        'type',
        'category_id',
        'subject_id',
        'competency_id',
        'title',
        'body',
        'explanation',
        'difficulty',
        'default_score',
        'config',
        'correct_answer',
        'metadata',
        'created_by',
        'is_active',
        'is_public',
        'is_survey_question',
    ];

    protected $casts = [
        'type' => QuestionTypeEnum::class,
        'difficulty' => DifficultyEnum::class,
        'default_score' => 'decimal:2',
        'config' => 'array',
        'correct_answer' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'is_survey_question' => 'boolean',
        'usage_count' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeFromBank($query)
    {
        return $query->where('is_survey_question', false);
    }

    public function scopeSurveyQuestions($query)
    {
        return $query->where('is_survey_question', true);
    }

    /** Model Relations -------------------------------------------------------------------------- */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(QuestionSubject::class, 'subject_id');
    }

    public function competency(): BelongsTo
    {
        return $this->belongsTo(QuestionCompetency::class, 'competency_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class)->orderBy('order');
    }

    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(Exam::class, 'exam_question')
            ->withPivot(['weight', 'order', 'is_required', 'config_override'])
            ->withTimestamps()
            ->orderByPivot('order');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(UserAnswer::class);
    }

    /** Model Custom Methods -------------------------------------------------------------------------- */
    public function typeHandler(): AbstractQuestionType
    {
        $handlerClass = $this->type->handler();

        return new $handlerClass($this);
    }

    public function validateData(array $data): array
    {
        $validator = validator(
            $data,
            $this->typeHandler()->validationRules(),
            $this->typeHandler()->validationMessages()
        );

        $validated = $validator->validated();

        if (method_exists($this->typeHandler(), 'afterValidation')) {
            return $this->typeHandler()->afterValidation($validated);
        }

        return $validated;
    }

    public function scoreAnswer(mixed $answer, ?float $weight = null): float
    {
        if ( ! $this->typeHandler()->validateAnswer($answer)) {
            return 0;
        }

        if ($weight !== null) {
            $this->setAttribute('weight', $weight);
        } else {
            $this->setAttribute('weight', $this->default_score);
        }

        return $this->typeHandler()->calculateScore($answer);
    }

    public function checkAnswer(mixed $answer): bool
    {
        return $this->typeHandler()->validateAnswer($answer);
    }

    public function getCorrectAnswer(): mixed
    {
        return $this->typeHandler()->getCorrectAnswer();
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    public function decrementUsage(): void
    {
        $this->decrement('usage_count');
    }

    public function duplicate(): self
    {
        $newQuestion = $this->replicate();
        $newQuestion->title = $this->title . ' (کپی)';
        $newQuestion->usage_count = 0;
        $newQuestion->save();

        foreach ($this->options as $option) {
            $newOption = $option->replicate();
            $newOption->question_id = $newQuestion->id;
            $newOption->save();
        }

        if (method_exists($this, 'tags') && $this->tags->isNotEmpty()) {
            $newQuestion->syncTags($this->tags);
        }

        return $newQuestion;
    }

    public function getStatistics(): array
    {
        $answers = $this->answers()
            ->whereNotNull('is_correct')
            ->get();

        $totalAnswers = $answers->count();
        $correctAnswers = $answers->where('is_correct', true)->count();

        return [
            'total_answers' => $totalAnswers,
            'correct_answers' => $correctAnswers,
            'incorrect_answers' => $totalAnswers - $correctAnswers,
            'success_rate' => $totalAnswers > 0
                ? round(($correctAnswers / $totalAnswers) * 100, 2)
                : 0,
            'average_score' => $answers->avg('score') ?? 0,
            'average_time' => $answers->avg('time_spent') ?? 0,
        ];
    }
}
