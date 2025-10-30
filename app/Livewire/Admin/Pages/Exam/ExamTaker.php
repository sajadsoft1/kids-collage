<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Exam;

use Exception;
use Livewire\Component;

class ExamTaker extends Component
{
    public Exam $exam;
    public ?ExamAttempt $attempt = null;
    public $currentQuestionIndex = 0;
    public $questions            = [];
    public $answers              = [];
    public $timeRemaining        = null;

    protected $listeners = [
        'answerChanged'                       => 'handleAnswerChanged',
        'echo:exam.{exam.id},ExamTimeExpired' => 'handleTimeExpired',
    ];

    public function mount(Exam $exam, ExamAttemptService $service)
    {
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

        // بارگذاری سوالات
        $this->questions = $exam->questions;

        if ($exam->shuffle_questions) {
            $this->questions = $this->questions->shuffle();
        }

        // بارگذاری پاسخ‌های قبلی
        foreach ($this->attempt->answers as $answer) {
            $this->answers[$answer->question_id] = $answer->answer_data;
        }

        $this->timeRemaining = $this->attempt->getRemainingTime();
    }

    public function handleAnswerChanged($answer)
    {
        $question = $this->questions[$this->currentQuestionIndex];

        $this->answers[$question->id] = $answer;

        // Auto-save
        app(ExamAttemptService::class)->submitAnswer(
            $this->attempt,
            $question,
            $answer
        );
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function goToQuestion($index)
    {
        if ($index >= 0 && $index < count($this->questions)) {
            $this->currentQuestionIndex = $index;
        }
    }

    public function submitExam(ExamAttemptService $service)
    {
        try {
            $service->complete($this->attempt);

            return redirect()->route('exams.results', $this->attempt);
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function handleTimeExpired()
    {
        app(ExamAttemptService::class)->complete($this->attempt);

        session()->flash('warning', 'زمان آزمون به پایان رسید');

        return redirect()->route('exams.results', $this->attempt);
    }

    public function render()
    {
        $currentQuestion = $this->questions[$this->currentQuestionIndex] ?? null;
        $progress        = app(ExamAttemptService::class)->getProgress($this->attempt);

        return view('livewire.admin.pages.exam.exam-taker', [
            'currentQuestion' => $currentQuestion,
            'progress'        => $progress,
            'totalQuestions'  => count($this->questions),
        ]);
    }
}
