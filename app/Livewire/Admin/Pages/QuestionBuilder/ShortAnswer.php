<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class ShortAnswer extends Component
{
    public array $config = [];

    public array $correct_answer = [
        'acceptable_answers' => [''],
    ];

    public function mount(array $config = [], ?array $correct_answer = null): void
    {
        $this->config = array_merge($this->getDefaultConfig(), $config);
        if ($correct_answer !== null) {
            $this->correct_answer = $correct_answer;
        }
    }

    protected function getDefaultConfig(): array
    {
        return [
            'max_length'      => 500,
            'case_sensitive'  => false,
            'trim_whitespace' => true,
            'auto_grade'      => false,
        ];
    }

    public function addAcceptable(): void
    {
        $this->correct_answer['acceptable_answers'][] = '';
        $this->dispatch('correctAnswerUpdated', $this->correct_answer);
    }

    public function removeAcceptable(int $index): void
    {
        if (count($this->correct_answer['acceptable_answers']) > 1) {
            unset($this->correct_answer['acceptable_answers'][$index]);
            $this->correct_answer['acceptable_answers'] = array_values($this->correct_answer['acceptable_answers']);
            $this->dispatch('correctAnswerUpdated', $this->correct_answer);
        }
    }

    public function updatedCorrectAnswer(): void
    {
        $this->dispatch('correctAnswerUpdated', $this->correct_answer);
    }

    public function updatedConfig(): void
    {
        $this->dispatch('configUpdated', $this->config);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-builder.short-answer');
    }
}
