<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionDisplay;

use Livewire\Component;

class MultipleChoice extends Component
{
    public Question $question;
    public $selectedOptions = [];
    public $options         = [];
    public $disabled        = false;
    public $showCorrect     = false;

    public function mount(Question $question, $value = [], $disabled = false, $showCorrect = false)
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

    public function toggleOption($optionId)
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

    public function render()
    {
        return view('livewire.admin.pages.question-display.multiple-choice');
    }
}
