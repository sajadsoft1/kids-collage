<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionDisplay;

use App\Models\Question;
use Livewire\Component;

class SingleChoice extends Component
{
    public Question $question;

    /** @var array<int,\App\Models\QuestionOption>|\Illuminate\Support\Collection */
    public $options = [];

    public ?int $selected = null;

    public bool $disabled = false;

    public bool $showCorrect = false;

    public function mount(Question $question, ?int $value = null, bool $disabled = false, bool $showCorrect = false): void
    {
        $this->question = $question;
        $this->selected = $value;
        $this->disabled = $disabled;
        $this->showCorrect = $showCorrect;

        $this->options = $question->options;

        if ($question->config['shuffle_options'] ?? false) {
            $this->options = $this->options->shuffle();
        }
    }

    public function choose(int $optionId): void
    {
        if ($this->disabled) {
            return;
        }
        $this->selected = $optionId;
        $this->dispatch('answerChanged', $this->selected);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-display.single-choice');
    }
}
