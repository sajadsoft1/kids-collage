<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class TextHighlight extends Component
{
    public array $config = [];

    public array $correct_answer = [
        'selections' => [],
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
            'allow_multiple' => true,
            'scoring_type'   => 'partial',
        ];
    }

    public function addSelection(): void
    {
        $this->correct_answer['selections'][] = ['start' => 0, 'end' => 0];
        $this->dispatch('correctAnswerUpdated', $this->correct_answer);
    }

    public function removeSelection(int $index): void
    {
        unset($this->correct_answer['selections'][$index]);
        $this->correct_answer['selections'] = array_values($this->correct_answer['selections']);
        $this->dispatch('correctAnswerUpdated', $this->correct_answer);
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
        return view('livewire.admin.pages.question-builder.text-highlight');
    }
}
