<?php

declare(strict_types=1);

namespace App\Models;

use App\Actions\Exam\EvaluateExamRulesAction;
use App\Enums\ExamStatusEnum;
use App\Enums\ExamTypeEnum;
use App\Traits\HasBranch;
use App\Traits\HasBranchScope;
use App\Traits\HasSchemalessAttributes;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

/**
 * @property string $title
 * @property string $description
 */
class Exam extends Model
{
    use HasBranch;
    use HasBranchScope;
    use HasFactory;
    use HasSchemalessAttributes;
    use HasTags;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'type',
        'total_score',
        'duration',
        'pass_score',
        'max_attempts',
        'shuffle_questions',
        'show_results',
        'allow_review',
        'settings',
        'starts_at',
        'ends_at',
        'status',
        'created_by',
        'branch_id',
        'extra_attributes',
    ];

    protected $casts = [
        'type' => ExamTypeEnum::class,
        'status' => ExamStatusEnum::class,
        'total_score' => 'decimal:2',
        'pass_score' => 'decimal:2',
        'duration' => 'integer',
        'max_attempts' => 'integer',
        'shuffle_questions' => 'boolean',
        'allow_review' => 'boolean',
        'settings' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /** Model Relations -------------------------------------------------------------------------- */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'exam_question')
            ->withPivot(['weight', 'order', 'is_required', 'config_override'])
            ->withTimestamps()
            ->orderByPivot('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    /** Model Scope -------------------------------------------------------------------------- */
    public function scopeActive($query)
    {
        return $query->published()
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }
    /**
     * Model Attributes --------------------------------------------------------------------------
     */

    /** Model Custom Methods -------------------------------------------------------------------------- */

    // ============================================
    // Question Management
    // ============================================

    public function getQuestionWeight(Question $question): float
    {
        return (float) $this->questions()
            ->where('question_id', $question->id)
            ->first()
            ?->pivot
            ->weight ?? 0.0;
    }

    public function getQuestionConfig(Question $question): ?array
    {
        $pivot = $this->questions()
            ->where('question_id', $question->id)
            ->first()
            ?->pivot;

        if ( ! $pivot) {
            return null;
        }

        $override = $pivot->config_override;

        return $override
            ? array_merge($question->config ?? [], $override)
            : $question->config;
    }

    public function hasQuestion(Question $question): bool
    {
        return $this->questions()->where('question_id', $question->id)->exists();
    }

    // ============================================
    // Validation
    // ============================================

    public function validateTotalWeight(): bool
    {
        if ($this->type !== ExamTypeEnum::SCORED) {
            return true;
        }

        if ( ! $this->total_score) {
            return false;
        }

        $totalWeight = $this->questions()->sum('exam_question.weight');

        return abs($totalWeight - $this->total_score) < 0.01;
    }

    public function canPublish(): array
    {
        $errors = [];

        if ($this->questions()->count() === 0) {
            $errors[] = 'آزمون باید حداقل یک سوال داشته باشد';
        }

        if ($this->type === ExamTypeEnum::SCORED && ! $this->validateTotalWeight()) {
            $errors[] = 'مجموع وزن سوالات با نمره کل آزمون برابر نیست';
        }

        return $errors;
    }

    // ============================================
    // Scoring
    // ============================================

    public function calculateAttemptScore(ExamAttempt $attempt): float
    {
        if ($this->type !== ExamTypeEnum::SCORED) {
            return 0;
        }

        return (float) $attempt->answers()
            ->whereNotNull('score')
            ->sum('score');
    }

    // ============================================
    // Status Management
    // ============================================

    public function publish(): bool
    {
        $errors = $this->canPublish();

        if ( ! empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }

        return $this->update(['status' => ExamStatusEnum::PUBLISHED]);
    }

    public function archive(): bool
    {
        return $this->update(['status' => ExamStatusEnum::ARCHIVED]);
    }

    public function unarchive(): bool
    {
        return $this->update(['status' => ExamStatusEnum::DRAFT]);
    }
    // ============================================
    // Attempt Management
    // ============================================

    public function canUserTakeExam(User $user): bool
    {
        // Check rules first if they exist
        if ($this->hasRules()) {
            $canParticipate = EvaluateExamRulesAction::run($this, $user);

            if ( ! $canParticipate) {
                return false;
            }
        }

        // Existing validation checks
        if ($this->status !== ExamStatusEnum::PUBLISHED) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        if ($this->max_attempts) {
            $attemptsCount = $this->attempts()
                ->where('user_id', $user->id)
                ->whereIn('status', ['completed'])
                ->count();

            if ($attemptsCount >= $this->max_attempts) {
                return false;
            }
        }

        return true;
    }

    public function getUserInProgressAttempt(User $user): ?ExamAttempt
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();
    }

    public function getUserAttemptsCount(User $user): int
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->whereIn('status', ['completed'])
            ->count();
    }

    // ============================================
    // Statistics
    // ============================================

    public function getStatistics(): array
    {
        $completedAttempts = $this->attempts()
            ->where('status', 'completed')
            ->get();

        return [
            'total_attempts' => $this->attempts()->count(),
            'in_progress_attempts' => $this->attempts()->where('status', 'in_progress')->count(),
            'completed_attempts' => $completedAttempts->count(),
            'average_score' => $completedAttempts->avg('total_score') ?? 0,
            'highest_score' => $completedAttempts->max('total_score') ?? 0,
            'lowest_score' => $completedAttempts->min('total_score') ?? 0,
            'pass_rate' => $this->pass_score && $completedAttempts->count() > 0
                ? round(($completedAttempts->where('total_score', '>=', $this->pass_score)->count() / $completedAttempts->count()) * 100, 2)
                : null,
        ];
    }

    // ============================================
    // Helpers
    // ============================================

    public function isActive(): bool
    {
        return $this->status === ExamStatusEnum::PUBLISHED
            && ( ! $this->starts_at || $this->starts_at->isPast())
            && ( ! $this->ends_at || $this->ends_at->isFuture());
    }

    public function isScored(): bool
    {
        return $this->type === ExamTypeEnum::SCORED;
    }

    public function isSurvey(): bool
    {
        return $this->type === ExamTypeEnum::SURVEY;
    }

    public function getQuestionsCount(): int
    {
        return $this->questions()->count();
    }

    // ============================================
    // Rules Management
    // ============================================

    /** Get rules from extra_attributes. */
    public function getRules(): ?array
    {
        return $this->extra_attributes->rules ?? null;
    }

    /** Check if exam has rules defined. */
    public function hasRules(): bool
    {
        $rules = $this->getRules();

        return $rules !== null && ! empty($rules);
    }

    /** Set rules in extra_attributes. */
    public function setRules(?array $rules): void
    {
        $this->extra_attributes->set('rules', $rules);
    }

    /** Scope: Get exams accessible by user (based on rules). */
    public function scopeAccessibleByUser($query, User $user)
    {
        return $query->where(function ($q) {
            $q->where(function ($subQuery) {
                // Exams without rules (accessible to all based on other criteria)
                $subQuery->whereNull(DB::raw("JSON_EXTRACT(extra_attributes, '$.rules')"));
            })->orWhere(function ($subQuery) {
                // Exams with rules - check if user can access
                $subQuery->whereNotNull(DB::raw("JSON_EXTRACT(extra_attributes, '$.rules')"));
            });
        });
    }

    /** Scope: Get exams user has attempted. */
    public function scopeWithUserAttempts($query, User $user)
    {
        return $query->whereHas('attempts', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }

    /** Scope: Get exams user can participate in (can take or has attempted). */
    public function scopeParticipableByUser($query, User $user)
    {
        $examIdsAccessible = [];

        // Get all exams and check rules for each
        $exams = Exam::all();

        foreach ($exams as $exam) {
            if ($exam->canUserTakeExam($user)) {
                $examIdsAccessible[] = $exam->id;
            }
        }

        return $query->whereIn('id', $examIdsAccessible)
            ->orWhereHas('attempts', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
    }
}
