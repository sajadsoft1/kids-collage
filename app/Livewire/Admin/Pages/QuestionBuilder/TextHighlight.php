<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class TextHighlight extends Component
{
    public array $config = [];
    public ?int $questionIndex = null;

    public ?array $correct_answer = null;

    public function mount(array $config = [], ?array $correct_answer = null, ?int $questionIndex = null): void
    {
        $this->config = array_merge($this->getDefaultConfig(), $config);
        $this->questionIndex = $questionIndex;
        if ($correct_answer !== null && isset($correct_answer['selections'])) {
            $this->correct_answer = $correct_answer;
        } else {
            // Initialize with default structure
            $this->correct_answer = [
                'selections' => [],
            ];
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
            'allow_multiple' => true,
            'scoring_type' => 'partial',
        ];
    }

    public function addSelection(): void
    {
        if ($this->correct_answer === null) {
            $this->correct_answer = [
                'selections' => [],
            ];
        }
        $this->correct_answer['selections'][] = ['start' => 0, 'end' => 0];
        $this->dispatchCorrectAnswer();
    }

    public function removeSelection(int $index): void
    {
        if ($this->correct_answer === null || !isset($this->correct_answer['selections'])) {
            return;
        }
        unset($this->correct_answer['selections'][$index]);
        $this->correct_answer['selections'] = array_values($this->correct_answer['selections']);
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
        if ($this->correct_answer === null) {
            return;
        }

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
        return view('livewire.admin.pages.question-builder.text-highlight');
    }
}
