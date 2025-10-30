<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionDisplay;

use App\Models\Question;
use Livewire\Component;

class TrueFalse extends Component
{
    public Question $question;

    public bool $disabled = false;

    public bool $showCorrect = false;

    public ?bool $selected = null;

    public function mount(Question $question, ?bool $value = null, bool $disabled = false, bool $showCorrect = false): void
    {
        $this->question    = $question;
        $this->selected    = $value;
        $this->disabled    = $disabled;
        $this->showCorrect = $showCorrect;
    }

    public function choose(bool $value): void
    {
        if ($this->disabled) {
            return;
        }
        $this->selected = $value;
        $this->dispatch('answerChanged', $this->selected);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-display.true-false');
    }
}
