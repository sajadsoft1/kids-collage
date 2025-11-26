<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class ShortAnswer extends Component
{
    public array $config = [];
    public ?int $questionIndex = null;

    public array $correct_answer = [
        'acceptable_answers' => [''],
    ];

    public function mount(array $config = [], ?array $correct_answer = null, ?int $questionIndex = null): void
    {
        $this->config = array_merge($this->getDefaultConfig(), $config);
        $this->questionIndex = $questionIndex;
        if ($correct_answer !== null) {
            $this->correct_answer = $correct_answer;
        }
        // Sync initial data
        $this->syncData();
    }

    public function dehydrate(): void
    {
        // Sync data before component dehydrates (sends to frontend)
        $this->syncData();
    }

    protected function syncData(): void
    {
        $this->dispatchConfig();
        $this->dispatchCorrectAnswer();
    }

    protected function getDefaultConfig(): array
    {
        return [
            'max_length' => 500,
            'case_sensitive' => false,
            'trim_whitespace' => true,
            'auto_grade' => false,
        ];
    }

    public function addAcceptable(): void
    {
        $this->correct_answer['acceptable_answers'][] = '';
        $this->dispatchCorrectAnswer();
    }

    public function removeAcceptable(int $index): void
    {
        if (count($this->correct_answer['acceptable_answers']) > 1) {
            unset($this->correct_answer['acceptable_answers'][$index]);
            $this->correct_answer['acceptable_answers'] = array_values($this->correct_answer['acceptable_answers']);
            $this->dispatchCorrectAnswer();
        }
    }

    protected function dispatchConfig(): void
    {
        if ($this->questionIndex !== null) {
            $this->dispatch('configUpdated', [
                'index' => $this->questionIndex,
                'config' => $this->config,
            ]);
        } else {
            $this->dispatch('configUpdated', [
                'config' => $this->config,
            ]);
        }
    }

    protected function dispatchCorrectAnswer(): void
    {
        if ($this->questionIndex !== null) {
            $this->dispatch('correctAnswerUpdated', [
                'index' => $this->questionIndex,
                'correct_answer' => $this->correct_answer,
            ]);
        } else {
            $this->dispatch('correctAnswerUpdated', [
                'correct_answer' => $this->correct_answer,
            ]);
        }
    }

    public function updatedCorrectAnswer(): void
    {
        $this->dispatchCorrectAnswer();
    }

    public function updatedConfig(): void
    {
        $this->dispatchConfig();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-builder.short-answer');
    }
}
