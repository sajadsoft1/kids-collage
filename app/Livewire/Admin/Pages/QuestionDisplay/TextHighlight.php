<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionDisplay;

use App\Models\Question;
use Livewire\Component;

class TextHighlight extends Component
{
    public Question $question;

    /** @var array{selections: array<int,array{start:int,end:int}>} */
    public array $value = ['selections' => []];

    public bool $disabled = false;

    public bool $showCorrect = false;

    public function mount(Question $question, ?array $value = null, bool $disabled = false, bool $showCorrect = false): void
    {
        $this->question    = $question;
        $this->value       = $value ?? ['selections' => []];
        $this->disabled    = $disabled;
        $this->showCorrect = $showCorrect;
    }

    public function addSelection(int $start, int $end): void
    {
        if ($this->disabled) {
            return;
        }
        $this->value['selections'][] = ['start' => $start, 'end' => $end];
        $this->dispatch('answerChanged', $this->value);
    }

    public function clearSelections(): void
    {
        if ($this->disabled) {
            return;
        }
        $this->value['selections'] = [];
        $this->dispatch('answerChanged', $this->value);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-display.text-highlight');
    }
}
