<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Exam;

use App\Enums\AttemptStatusEnum;
use App\Enums\ShowResultsEnum;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Question;
use App\Services\ExamAttemptService;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ExamTaker extends Component
{
    // ══════════════════════════════════════════════════════════════════════════
    // LOCKED PROPERTIES - تغییر از سمت کلاینت ممکن نیست
    // ══════════════════════════════════════════════════════════════════════════

    #[Locked]
    public int $examId;

    #[Locked]
    public int $attemptId;

    #[Locked]
    public bool $reviewMode = false;

    #[Locked]
    public bool $isImmediateMode = false;

    #[Locked]
    public array $questionIds = [];

    // ══════════════════════════════════════════════════════════════════════════
    // PUBLIC PROPERTIES
    // ══════════════════════════════════════════════════════════════════════════

    public int $currentQuestionIndex = 0;

    /** @var array<int, mixed> question_id => answer_data */
    public array $answers = [];

    public ?int $timeRemaining = null;

    /** زمان ورود به سوال فعلی */
    public ?int $currentQuestionEnteredAt = null;

    /** @var array<int, bool> question_id => true برای جستجوی O(1) */
    public array $lockedQuestions = [];

    // ══════════════════════════════════════════════════════════════════════════
    // LISTENERS
    // ══════════════════════════════════════════════════════════════════════════

    protected $listeners = [
        'answerChanged' => 'handleAnswerChanged',
    ];

    // ══════════════════════════════════════════════════════════════════════════
    // CACHED DATA - برای جلوگیری از کوئری‌های مکرر
    // ══════════════════════════════════════════════════════════════════════════

    /** @var Collection<int, Question>|null */
    private ?Collection $questionsCache = null;

    private ?Exam $examCache = null;

    private ?ExamAttempt $attemptCache = null;

    // ══════════════════════════════════════════════════════════════════════════
    // MOUNT - فقط یکبار در شروع اجرا میشه
    // ══════════════════════════════════════════════════════════════════════════

    public function mount(Exam $exam, ExamAttemptService $service, ?ExamAttempt $attempt): void
    {
        $this->examId = $exam->id;
        $this->examCache = $exam;

        // تشخیص حالت immediate
        $this->isImmediateMode = $exam->show_results === ShowResultsEnum::IMMEDIATE
            || $exam->show_results                   === ShowResultsEnum::IMMEDIATE->value;

        // اگر attempt پاس داده شده
        if ($attempt?->id) {
            $this->attemptId = $attempt->id;
            $this->attemptCache = $attempt;

            // بررسی دسترسی
            $this->authorizeAttemptAccess($attempt);

            // تشخیص حالت بازبینی بر اساس وضعیت attempt
            $this->reviewMode = $this->isAttemptFinished($attempt);

            if ( ! $this->reviewMode) {
                $this->initializeTimer();
            }
        } else {
            // شروع یا ادامه آزمون
            $this->initializeNewAttempt($exam, $service);
        }

        // بارگذاری سوالات (یکبار)
        $this->loadQuestions($exam);

        // بارگذاری پاسخ‌های قبلی
        $this->loadPreviousAnswers();

        // در حالت immediate، بازیابی سوالات قفل شده از metadata
        $this->loadLockedQuestionsFromMetadata();
    }

    // ══════════════════════════════════════════════════════════════════════════
    // COMPUTED PROPERTIES - کش میشن تا پایان request
    // ══════════════════════════════════════════════════════════════════════════

    #[Computed(persist: true)]
    public function exam(): Exam
    {
        return $this->examCache ??= Exam::findOrFail($this->examId);
    }

    #[Computed(persist: true)]
    public function attempt(): ExamAttempt
    {
        return $this->attemptCache ??= ExamAttempt::with(['answers', 'user'])->findOrFail($this->attemptId);
    }

    #[Computed]
    public function questions(): Collection
    {
        if ($this->questionsCache !== null) {
            return $this->questionsCache;
        }

        if (empty($this->questionIds)) {
            return $this->questionsCache = collect();
        }

        // Load questions و key by id برای دسترسی O(1)
        return $this->questionsCache = Question::with('options')
            ->whereIn('id', $this->questionIds)
            ->get()
            ->keyBy('id');
    }

    #[Computed]
    public function currentQuestion(): ?Question
    {
        $questionId = $this->currentQuestionId;

        return $questionId ? $this->questions->get($questionId) : null;
    }

    #[Computed]
    public function currentQuestionId(): ?int
    {
        return $this->questionIds[$this->currentQuestionIndex] ?? null;
    }

    #[Computed]
    public function isCurrentQuestionLocked(): bool
    {
        $questionId = $this->currentQuestionId;

        return $questionId && isset($this->lockedQuestions[$questionId]);
    }

    #[Computed]
    public function isLastQuestion(): bool
    {
        return $this->currentQuestionIndex === count($this->questionIds) - 1;
    }

    #[Computed]
    public function totalQuestions(): int
    {
        return count($this->questionIds);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // ACTIONS
    // ══════════════════════════════════════════════════════════════════════════

    public function handleAnswerChanged(mixed $answer): void
    {
        if ($this->reviewMode) {
            return;
        }

        $questionId = $this->currentQuestionId;
        if ( ! $questionId) {
            return;
        }

        // اگر سوال قفل شده، امکان تغییر نیست
        if (isset($this->lockedQuestions[$questionId])) {
            return;
        }

        $this->answers[$questionId] = $answer;

        // محاسبه زمان و ذخیره پاسخ
        $timeSpent = $this->getTimeSpentOnCurrentQuestion();
        $this->saveAnswer($questionId, $answer, $timeSpent);

        // ریست تایمر
        $this->currentQuestionEnteredAt = time();
    }

    public function lockCurrentAnswer(): void
    {
        if ($this->reviewMode || ! $this->isImmediateMode) {
            return;
        }

        $questionId = $this->currentQuestionId;
        if ( ! $questionId || ! isset($this->answers[$questionId])) {
            return;
        }

        // اگر قبلاً قفل شده
        if (isset($this->lockedQuestions[$questionId])) {
            return;
        }

        // ذخیره زمان
        $this->saveCurrentQuestionTime();

        // قفل کردن
        $this->lockedQuestions[$questionId] = true;

        // ذخیره در metadata برای resume
        $this->saveLockedQuestionsToMetadata();
    }

    public function nextQuestion(): void
    {
        if ($this->currentQuestionIndex < $this->totalQuestions - 1) {
            $this->saveCurrentQuestionTime();
            $this->currentQuestionIndex++;
            $this->currentQuestionEnteredAt = time();

            // پاک کردن کش سوال فعلی
            unset($this->currentQuestion, $this->currentQuestionId, $this->isCurrentQuestionLocked);
        }
    }

    public function previousQuestion(): void
    {
        if ($this->currentQuestionIndex > 0) {
            $this->saveCurrentQuestionTime();
            $this->currentQuestionIndex--;
            $this->currentQuestionEnteredAt = time();

            unset($this->currentQuestion, $this->currentQuestionId, $this->isCurrentQuestionLocked);
        }
    }

    public function goToQuestion(int $index): void
    {
        if ($index >= 0 && $index < $this->totalQuestions && $index !== $this->currentQuestionIndex) {
            $this->saveCurrentQuestionTime();
            $this->currentQuestionIndex = $index;
            $this->currentQuestionEnteredAt = time();

            unset($this->currentQuestion, $this->currentQuestionId, $this->isCurrentQuestionLocked);
        }
    }

    public function submitExam(): mixed
    {
        try {
            $this->saveCurrentQuestionTime();

            app(ExamAttemptService::class)->complete($this->attempt);

            session()->flash('success', __('exam.exam_completed'));

            return redirect()->route('admin.exam.attempt.results', [
                'exam' => $this->examId,
                'attempt' => $this->attemptId,
            ]);
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());

            return null;
        }
    }

    public function suspendExam(): mixed
    {
        $this->saveCurrentQuestionTime();
        session()->flash('info', __('exam.exam_suspended'));

        return redirect()->route('admin.exam.index');
    }

    public function handleTimeExpired(): mixed
    {
        app(ExamAttemptService::class)->complete($this->attempt);

        session()->flash('warning', __('exam.time_expired'));

        return redirect()->route('admin.exam.attempt.results', [
            'exam' => $this->examId,
            'attempt' => $this->attemptId,
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // RENDER
    // ══════════════════════════════════════════════════════════════════════════

    public function render()
    {
        $currentQuestion = $this->currentQuestion;

        // Get pivot data
        $pivotData = $currentQuestion
            ? $this->exam->questions()
                ->where('questions.id', $currentQuestion->id)
                ->first()?->pivot
            : null;

        return view('livewire.admin.pages.exam.exam-taker', [
            'exam' => $this->exam,
            'attempt' => $this->attempt,
            'currentQuestion' => $currentQuestion,
            'pivotData' => $pivotData,
            'progress' => app(ExamAttemptService::class)->getProgress($this->attempt),
            'totalQuestions' => $this->totalQuestions,
            'isLastQuestion' => $this->isLastQuestion,
            'reviewMode' => $this->reviewMode,
            'isImmediateMode' => $this->isImmediateMode,
            'isCurrentQuestionLocked' => $this->isCurrentQuestionLocked,
        ])->layout('components.layouts.exam');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // PRIVATE METHODS
    // ══════════════════════════════════════════════════════════════════════════

    private function authorizeAttemptAccess(ExamAttempt $attempt): void
    {
        $userId = auth()->id();
        $user = auth()->user();

        if ($attempt->user_id !== $userId && ! $user?->can('viewAny', ExamAttempt::class)) {
            abort(403, 'شما مجاز به مشاهده این تلاش نیستید');
        }
    }

    private function isAttemptFinished(ExamAttempt $attempt): bool
    {
        $status = $attempt->status instanceof AttemptStatusEnum
            ? $attempt->status
            : AttemptStatusEnum::tryFrom($attempt->status);

        return $status?->isFinished() ?? false;
    }

    private function initializeTimer(): void
    {
        $this->currentQuestionEnteredAt = time();
        $this->timeRemaining = $this->attempt->getRemainingTime();
    }

    private function initializeNewAttempt(Exam $exam, ExamAttemptService $service): void
    {
        $user = auth()->user();

        if ( ! $exam->canUserTakeExam($user)) {
            abort(403, 'شما مجاز به شرکت در این آزمون نیستید');
        }

        // شروع یا ادامه آزمون
        $inProgress = $exam->getUserInProgressAttempt($user);

        $this->attemptCache = $inProgress ?? $service->start($exam, $user);
        $this->attemptId = $this->attemptCache->id;

        $this->initializeTimer();
    }

    private function loadQuestions(Exam $exam): void
    {
        $questions = $exam->questions()
            ->orderBy('exam_question.order')
            ->get();

        // Shuffle فقط در حالت غیر بازبینی
        if ( ! $this->reviewMode && $exam->shuffle_questions) {
            $questions = $questions->shuffle()->values();
        }

        $this->questionIds = $questions->pluck('id')->toArray();
    }

    private function loadPreviousAnswers(): void
    {
        // Eager load answers
        $answers = $this->attempt->answers()->get(['question_id', 'answer_data']);

        foreach ($answers as $answer) {
            $this->answers[$answer->question_id] = $answer->answer_data;
        }
    }

    private function loadLockedQuestionsFromMetadata(): void
    {
        if ( ! $this->isImmediateMode || $this->reviewMode) {
            return;
        }

        // بارگذاری از metadata
        $metadata = $this->attempt->metadata ?? [];
        $lockedIds = $metadata['locked_questions'] ?? [];

        // تبدیل به associative array برای O(1) lookup
        $this->lockedQuestions = array_fill_keys($lockedIds, true);
    }

    private function saveLockedQuestionsToMetadata(): void
    {
        $metadata = $this->attempt->metadata ?? [];
        $metadata['locked_questions'] = array_keys($this->lockedQuestions);

        $this->attempt->update(['metadata' => $metadata]);
    }

    private function getTimeSpentOnCurrentQuestion(): int
    {
        if ($this->currentQuestionEnteredAt === null) {
            return 0;
        }

        return time() - $this->currentQuestionEnteredAt;
    }

    private function saveCurrentQuestionTime(): void
    {
        // در حالت بازبینی، زمان ذخیره نمی‌شود
        if ($this->reviewMode) {
            return;
        }

        $questionId = $this->currentQuestionId;

        if ( ! $questionId || ! isset($this->answers[$questionId])) {
            return;
        }

        $timeSpent = $this->getTimeSpentOnCurrentQuestion();
        if ($timeSpent > 0) {
            $this->saveAnswer($questionId, $this->answers[$questionId], $timeSpent);
        }
    }

    private function saveAnswer(int $questionId, mixed $answer, int $timeSpent): void
    {
        // در حالت بازبینی، پاسخ ذخیره نمی‌شود
        if ($this->reviewMode) {
            return;
        }

        $question = $this->questions->get($questionId);

        if ($question) {
            app(ExamAttemptService::class)->submitAnswer(
                $this->attempt,
                $question,
                $answer,
                $timeSpent
            );
        }
    }
}
