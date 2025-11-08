<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Survey;

use App\Enums\ExamTypeEnum;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Services\ExamAttemptService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

class SurveyTaker extends Component
{
    use Toast;

    public Exam $exam;

    public ?ExamAttempt $attempt = null;

    public int $currentQuestionIndex = 0;

    public Collection $questions;

    public array $answers = [];

    public ?int $timeRemaining = null;

    protected $listeners = [
        'answerChanged' => 'handleAnswerChanged',
    ];

    public function mount(Exam $exam, ExamAttemptService $service): void
    {
        $user = Auth::user();
        abort_if($user === null, 403);
        abort_if($exam->type !== ExamTypeEnum::SURVEY, 404);

        $this->exam = $exam;

        $inProgressAttempt = $exam->getUserInProgressAttempt($user);

        if ($inProgressAttempt !== null) {
            $this->attempt = $inProgressAttempt;
        } elseif ($exam->canUserTakeExam($user)) {
            $this->attempt = $service->start($exam, $user);
        } else {
            $latestAttempt = $exam->attempts()
                ->where('user_id', $user->id)
                ->latest('updated_at')
                ->first();

            abort_if($latestAttempt === null, 403, 'دسترسی به این نظر سنجی امکان‌پذیر نیست.');

            $this->attempt = $latestAttempt;
        }

        $questions = $exam->questions()
            ->with('options')
            ->orderBy('exam_question.order')
            ->get();

        if ($exam->shuffle_questions) {
            $questions = $questions->shuffle()->values();
        }

        $this->questions = $questions;

        foreach ($this->attempt->answers as $answer) {
            $this->answers[$answer->question_id] = $answer->answer_data;
        }

        $this->timeRemaining = $this->attempt->getRemainingTime();
    }

    public function handleAnswerChanged(mixed $answer): void
    {
        if ( ! $this->attempt->canContinue()) {
            return;
        }

        $question = $this->questions[$this->currentQuestionIndex] ?? null;

        if ($question === null) {
            return;
        }

        $this->answers[$question->id] = $answer;

        try {
            app(ExamAttemptService::class)->submitAnswer(
                $this->attempt,
                $question,
                $answer
            );
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());
        }
    }

    public function nextQuestion(): void
    {
        if ($this->currentQuestionIndex < $this->questions->count() - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion(): void
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function goToQuestion(int $index): void
    {
        if ($index >= 0 && $index < $this->questions->count()) {
            $this->currentQuestionIndex = $index;
        }
    }

    public function submitSurvey(ExamAttemptService $service): void
    {
        if ( ! $this->attempt->canContinue()) {
            $this->info('این نظر سنجی قبلاً ثبت نهایی شده است.');

            return;
        }

        try {
            $service->complete($this->attempt);

            $this->success('پاسخ‌های شما با موفقیت ثبت شد.');

            $this->redirect(route('admin.survey.index'), true);
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());
        }
    }

    public function render(): View
    {
        $currentQuestion = $this->questions[$this->currentQuestionIndex] ?? null;

        $progress = $this->attempt
            ? app(ExamAttemptService::class)->getProgress($this->attempt)
            : [
                'total_questions'     => $this->questions->count(),
                'answered_questions'  => 0,
                'remaining_questions' => $this->questions->count(),
                'progress_percentage' => 0,
                'remaining_time'      => $this->timeRemaining,
                'elapsed_time'        => 0,
            ];

        return view('livewire.admin.pages.survey.survey-taker', [
            'currentQuestion' => $currentQuestion,
            'totalQuestions'  => $this->questions->count(),
            'progress'        => $progress,
            'canContinue'     => $this->attempt->canContinue(),
            'breadcrumbs'     => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.survey.index'), 'label' => trans('_menu.survey_management')],
                ['label' => $this->exam->title],
            ],
        ]);
    }
}
