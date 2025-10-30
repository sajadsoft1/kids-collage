<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionDisplay;

use Livewire\Component;

class SingleChoice extends Component
{
    public Question $question;
    public $selectedOption = null;
    public $options        = [];
    public $disabled       = false;
    public $showCorrect    = false;

    public function mount(Question $question, $value = null, $disabled = false, $showCorrect = false)
    {
        $this->question       = $question;
        $this->selectedOption = $value;
        $this->disabled       = $disabled;
        $this->showCorrect    = $showCorrect;

        $this->options = $question->options;

        // Shuffle if configured
        if ($question->config['shuffle_options'] ?? false) {
            $this->options = $this->options->shuffle();
        }
    }

    public function selectOption($optionId)
    {
        if ( ! $this->disabled) {
            $this->selectedOption = $optionId;
            $this->dispatch('answerChanged', $this->selectedOption);
        }
    }

    public function render()
    {
        return view('livewire.admin.pages.question-display.single-choice');
    }
}
