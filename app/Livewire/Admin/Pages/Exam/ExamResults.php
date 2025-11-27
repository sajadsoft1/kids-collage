<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Exam;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\UserAnswer;
use App\Services\ExamAttemptService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ExamResults extends Component
{
    public Exam $exam;

    public ExamAttempt $attempt;

    public array $results = [];

    public bool $showAnswers = false;

    public string $selectedTab = 'overview';

    public bool $showQuestionModal = false;

    public ?int $selectedAnswerId = null;

    public string $modalTab = 'question';

    public function mount(Exam $exam, ExamAttempt $attempt, ExamAttemptService $service): void
    {
        $this->exam = $exam;
        $this->attempt = $attempt;
        $this->results = $service->getResults($attempt);

        // بررسی اینکه آیا نمایش پاسخ‌ها مجاز است
        $this->showAnswers = in_array(
            $attempt->exam->show_results,
            ['immediate', 'after_submit']
        );
    }

    /** دریافت پاسخ‌های آزمون */
    #[Computed]
    public function answers(): Collection
    {
        if ( ! $this->showAnswers) {
            return collect();
        }

        return $this->attempt->answers()
            ->with(['question.options'])
            ->get();
    }

    /** دریافت پاسخ انتخاب شده برای مودال */
    #[Computed]
    public function selectedAnswer(): ?UserAnswer
    {
        if ( ! $this->selectedAnswerId) {
            return null;
        }

        return $this->answers->firstWhere('id', $this->selectedAnswerId);
    }

    /** آمار عملکرد سوالات */
    #[Computed]
    public function questionPerformance(): array
    {
        $answers = $this->answers;

        return [
            'correct' => $answers->where('is_correct', true)->count(),
            'incorrect' => $answers->where('is_correct', false)->whereNotNull('answer_data')->count(),
            'omitted' => $answers->whereNull('answer_data')->count() + ($this->results['total_questions'] - $answers->count()),
        ];
    }

    /** نمایش مودال سوال */
    public function showQuestion(int $answerId): void
    {
        $this->selectedAnswerId = $answerId;
        $this->modalTab = 'question';
        $this->showQuestionModal = true;
    }

    /** بستن مودال */
    public function closeQuestionModal(): void
    {
        $this->showQuestionModal = false;
        $this->selectedAnswerId = null;
        $this->modalTab = 'question';
    }

    public function render()
    {
        return view('livewire.admin.pages.exam.exam-results');
    }
}
