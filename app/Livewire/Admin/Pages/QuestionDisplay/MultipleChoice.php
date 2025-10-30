<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionDisplay;

use App\Models\Question;
use Livewire\Component;

class MultipleChoice extends Component
{
    /** @var array<int,int> */
    public array $selectedOptions = [];

    /** @var array<int,\App\Models\QuestionOption>|\Illuminate\Support\Collection */
    public $options = [];

    public bool $disabled = false;

    public bool $showCorrect = false;

    public Question $question;

    public function mount(Question $question, array $value = [], bool $disabled = false, bool $showCorrect = false): void
    {
        $this->question        = $question;
        $this->selectedOptions = $value ?? [];
        $this->disabled        = $disabled;
        $this->showCorrect     = $showCorrect;

        $this->options = $question->options;

        if ($question->config['shuffle_options'] ?? false) {
            $this->options = $this->options->shuffle();
        }
    }

    public function toggleOption(int $optionId): void
    {
        if ( ! $this->disabled) {
            if (in_array($optionId, $this->selectedOptions)) {
                $this->selectedOptions = array_values(
                    array_diff($this->selectedOptions, [$optionId])
                );
            } else {
                $this->selectedOptions[] = $optionId;
            }

            $this->dispatch('answerChanged', $this->selectedOptions);
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-display.multiple-choice');
    }
}
