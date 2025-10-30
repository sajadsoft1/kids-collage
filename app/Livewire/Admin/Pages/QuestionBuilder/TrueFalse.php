<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class TrueFalse extends Component
{
    public array $config = [];

    public array $correct_answer = [
        'value' => false,
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
            'true_label'  => 'درست',
            'false_label' => 'غلط',
        ];
    }

    public function setAnswer(bool $value): void
    {
        $this->correct_answer['value'] = $value;
        $this->dispatch('correctAnswerUpdated', $this->correct_answer);
    }

    public function updatedConfig(): void
    {
        $this->dispatch('configUpdated', $this->config);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-builder.true-false');
    }
}
