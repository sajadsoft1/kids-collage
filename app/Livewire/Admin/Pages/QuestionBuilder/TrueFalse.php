<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class TrueFalse extends Component
{
    public array $config = [];
    public ?int $questionIndex = null;

    public array $correct_answer = [
        'value' => false,
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
            'true_label' => 'درست',
            'false_label' => 'غلط',
        ];
    }

    public function setAnswer(bool $value): void
    {
        $this->correct_answer['value'] = $value;
        $this->dispatchCorrectAnswer();
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

    public function updatedConfig(): void
    {
        $this->dispatchConfig();
    }

    public function updatedCorrectAnswer(): void
    {
        $this->dispatchCorrectAnswer();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-builder.true-false');
    }
}
