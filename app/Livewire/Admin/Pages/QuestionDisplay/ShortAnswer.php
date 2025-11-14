<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionDisplay;

use App\Models\Question;
use Livewire\Component;

class ShortAnswer extends Component
{
    public Question $question;

    public string $value = '';

    public bool $disabled = false;

    public bool $showCorrect = false;

    public function mount(Question $question, string $value = '', bool $disabled = false, bool $showCorrect = false): void
    {
        $this->question = $question;
        $this->value = $value;
        $this->disabled = $disabled;
        $this->showCorrect = $showCorrect;
    }

    public function updatedValue(): void
    {
        $this->dispatch('answerChanged', $this->value);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-display.short-answer');
    }
}
