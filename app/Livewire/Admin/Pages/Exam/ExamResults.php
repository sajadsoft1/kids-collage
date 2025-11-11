<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Exam;

use Livewire\Component;

class ExamResults extends Component
{
    public ExamAttempt $attempt;
    public $results = [];
    public $showAnswers = false;

    public function mount(ExamAttempt $attempt, ExamAttemptService $service)
    {
        $this->authorize('view', $attempt);

        $this->attempt = $attempt;
        $this->results = $service->getResults($attempt);

        // بررسی اینکه آیا نمایش پاسخ‌ها مجاز است
        $this->showAnswers = in_array(
            $attempt->exam->show_results,
            ['immediate', 'after_submit']
        );
    }

    public function render()
    {
        return view('livewire.admin.pages.exam.exam-results', [
            'answers' => $this->showAnswers
              ? $this->attempt->answers()->with('question.options')->get()
              : collect(),
        ]);
    }
}
