<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionDisplay;

use App\Models\Question;
use Livewire\Component;

class Ordering extends Component
{
    public Question $question;

    /** @var array<int,int> */
    public array $order = [];

    /** @var array<int,\App\Models\QuestionOption>|\Illuminate\Support\Collection */
    public $options = [];

    public bool $disabled = false;

    public bool $showCorrect = false;

    public function mount(Question $question, array $value = [], bool $disabled = false, bool $showCorrect = false): void
    {
        $this->question    = $question;
        $this->options     = $question->options;
        $this->disabled    = $disabled;
        $this->showCorrect = $showCorrect;

        $initial     = $value ?: $this->options->pluck('id')->toArray();
        $this->order = array_values($initial);
    }

    public function moveUp(int $index): void
    {
        if ($this->disabled || $index <= 0) {
            return;
        }
        [$this->order[$index - 1], $this->order[$index]] = [$this->order[$index], $this->order[$index - 1]];
        $this->dispatch('answerChanged', $this->order);
    }

    public function moveDown(int $index): void
    {
        if ($this->disabled || $index >= count($this->order) - 1) {
            return;
        }
        [$this->order[$index + 1], $this->order[$index]] = [$this->order[$index], $this->order[$index + 1]];
        $this->dispatch('answerChanged', $this->order);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-display.ordering');
    }
}
