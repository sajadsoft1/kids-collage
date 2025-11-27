<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Exam;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Question;
use App\Services\ExamAttemptService;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Component;

class ExamTaker extends Component
{
    public Exam $exam;

    public ?ExamAttempt $attempt = null;

    public int $currentQuestionIndex = 0;

    /** @var array<int> Question IDs in order */
    public array $questionIds = [];

    /** @var array<int, mixed> */
    public array $answers = [];

    public ?int $timeRemaining = null;

    /** @var array<int, int> زمان‌های ذخیره شده برای هر سوال */
    public array $questionTimestamps = [];

    /** زمان ورود به سوال فعلی */
    public ?int $currentQuestionEnteredAt = null;

    /** حالت بازبینی - فقط نمایش سوالات و پاسخ‌ها بدون امکان تغییر */
    public bool $reviewMode = false;

    protected $listeners = [
        'answerChanged' => 'handleAnswerChanged',
    ];

    public function mount(Exam $exam, ExamAttemptService $service, ?ExamAttempt $attempt): void
    {
        $this->exam = $exam;

        // اگر attempt پاس داده شده، حالت بازبینی فعال است
        if ($attempt->id > 0) {
            $this->reviewMode = true;
            $this->attempt = $attempt;

            // بررسی دسترسی کاربر به این تلاش
            if ($attempt->user_id !== auth()->id() && ! auth()->user()->can('viewAny', ExamAttempt::class)) {
                abort(403, 'شما مجاز به مشاهده این تلاش نیستید');
            }
        } else {
            // حالت عادی آزمون دادن
            if ( ! $exam->canUserTakeExam(auth()->user())) {
                abort(403, 'شما مجاز به شرکت در این آزمون نیستید');
            }

            // شروع یا ادامه آزمون
            $inProgress = $exam->getUserInProgressAttempt(auth()->user());

            if ($inProgress) {
                $this->attempt = $inProgress;
            } else {
                $this->attempt = $service->start($exam, auth()->user());
            }

            // شروع ردگیری زمان برای سوال اول
            $this->currentQuestionEnteredAt = time();
            $this->timeRemaining = $this->attempt->getRemainingTime();
        }

        // بارگذاری آیدی سوالات
        $questions = $exam->questions()
            ->orderBy('exam_question.order')
            ->get();

        // در حالت عادی shuffle کن، در حالت بازبینی ترتیب ثابت
        if ( ! $this->reviewMode && $exam->shuffle_questions) {
            $questions = $questions->shuffle()->values();
        }

        $this->questionIds = $questions->pluck('id')->toArray();

        // بارگذاری پاسخ‌های قبلی
        foreach ($this->attempt->answers as $answer) {
            $this->answers[$answer->question_id] = $answer->answer_data;
        }
    }

    /** Get questions collection for the view */
    public function getQuestionsProperty(): Collection
    {
        if (empty($this->questionIds)) {
            return collect();
        }

        $questions = Question::with('options')
            ->whereIn('id', $this->questionIds)
            ->get()
            ->keyBy('id');

        // حفظ ترتیب اصلی
        return collect($this->questionIds)->map(fn ($id) => $questions->get($id))->filter();
    }

    /** Get current question */
    public function getCurrentQuestionProperty(): ?Question
    {
        $questionId = $this->questionIds[$this->currentQuestionIndex] ?? null;

        if ( ! $questionId) {
            return null;
        }

        return Question::with('options')->find($questionId);
    }

    public function handleAnswerChanged($answer): void
    {
        // در حالت بازبینی امکان تغییر پاسخ وجود ندارد
        if ($this->reviewMode) {
            return;
        }

        $questionId = $this->questionIds[$this->currentQuestionIndex] ?? null;

        if ( ! $questionId) {
            return;
        }

        $this->answers[$questionId] = $answer;

        // محاسبه زمان سپری شده روی این سوال
        $timeSpent = $this->getTimeSpentOnCurrentQuestion();

        // Auto-save
        $question = Question::find($questionId);
        if ($question) {
            app(ExamAttemptService::class)->submitAnswer(
                $this->attempt,
                $question,
                $answer,
                $timeSpent
            );
        }

        // ریست تایمر برای ادامه ردگیری
        $this->currentQuestionEnteredAt = time();
    }

    /** محاسبه زمان سپری شده روی سوال فعلی (به ثانیه) */
    private function getTimeSpentOnCurrentQuestion(): int
    {
        if ($this->currentQuestionEnteredAt === null) {
            return 0;
        }

        return time() - $this->currentQuestionEnteredAt;
    }

    /** ذخیره زمان سوال فعلی قبل از ترک */
    private function saveCurrentQuestionTime(): void
    {
        $questionId = $this->questionIds[$this->currentQuestionIndex] ?? null;
        if ($questionId && isset($this->answers[$questionId])) {
            $timeSpent = $this->getTimeSpentOnCurrentQuestion();
            $question = Question::find($questionId);

            if ($question && $timeSpent > 0) {
                app(ExamAttemptService::class)->submitAnswer(
                    $this->attempt,
                    $question,
                    $this->answers[$questionId],
                    $timeSpent
                );
            }
        }
    }

    public function nextQuestion(): void
    {
        if ($this->currentQuestionIndex < count($this->questionIds) - 1) {
            // ذخیره زمان سوال فعلی قبل از رفتن به سوال بعدی
            $this->saveCurrentQuestionTime();

            $this->currentQuestionIndex++;

            // شروع ردگیری زمان برای سوال جدید
            $this->currentQuestionEnteredAt = time();
        }
    }

    public function previousQuestion(): void
    {
        if ($this->currentQuestionIndex > 0) {
            // ذخیره زمان سوال فعلی قبل از رفتن به سوال قبلی
            $this->saveCurrentQuestionTime();

            $this->currentQuestionIndex--;

            // شروع ردگیری زمان برای سوال جدید
            $this->currentQuestionEnteredAt = time();
        }
    }

    public function goToQuestion(int $index): void
    {
        if ($index >= 0 && $index < count($this->questionIds) && $index !== $this->currentQuestionIndex) {
            // ذخیره زمان سوال فعلی قبل از پرش
            $this->saveCurrentQuestionTime();

            $this->currentQuestionIndex = $index;

            // شروع ردگیری زمان برای سوال جدید
            $this->currentQuestionEnteredAt = time();
        }
    }

    /** Check if current question is the last one */
    public function getIsLastQuestionProperty(): bool
    {
        return $this->currentQuestionIndex === count($this->questionIds) - 1;
    }

    /** اتمام آزمون - پایان دادن به آزمون و نمایش نتیجه */
    public function submitExam(ExamAttemptService $service)
    {
        try {
            // ذخیره زمان آخرین سوال قبل از تکمیل آزمون
            $this->saveCurrentQuestionTime();

            $service->complete($this->attempt);

            session()->flash('success', __('exam.exam_completed'));

            return redirect()->route('admin.exam.attempt.results', [
                'exam' => $this->exam->id,
                'attempt' => $this->attempt->id,
            ]);
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    /** توقف موقت - خروج از صفحه بدون پایان آزمون (امکان ادامه) */
    public function suspendExam()
    {
        // آزمون در وضعیت in_progress باقی می‌ماند
        // تایمر در دیتابیس ذخیره شده و هنگام بازگشت ادامه پیدا می‌کند
        session()->flash('info', __('exam.exam_suspended'));

        return redirect()->route('admin.exam.index');
    }

    /** زمان آزمون تمام شد */
    public function handleTimeExpired()
    {
        app(ExamAttemptService::class)->complete($this->attempt);

        session()->flash('warning', __('exam.time_expired'));

        return redirect()->route('admin.exam.attempt.results', [
            'exam' => $this->exam->id,
            'attempt' => $this->attempt->id,
        ]);
    }

    public function render()
    {
        $currentQuestion = $this->currentQuestion;

        // Get pivot data for the current question
        $pivotData = null;
        if ($currentQuestion) {
            $pivotData = $this->exam->questions()
                ->where('questions.id', $currentQuestion->id)
                ->first()?->pivot;
        }

        $progress = app(ExamAttemptService::class)->getProgress($this->attempt);

        return view('livewire.admin.pages.exam.exam-taker', [
            'currentQuestion' => $currentQuestion,
            'pivotData' => $pivotData,
            'progress' => $progress,
            'totalQuestions' => count($this->questionIds),
            'isLastQuestion' => $this->isLastQuestion,
            'reviewMode' => $this->reviewMode,
        ])->layout('components.layouts.exam');
    }
}
