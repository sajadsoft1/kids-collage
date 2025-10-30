<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionDisplay;

use Livewire\Component;

class Ordering extends Component
{
    public Question $question;
    public $orderedOptions = [];
    public $disabled       = false;
    public $showCorrect    = false;

    public function mount(Question $question, $value = [], $disabled = false, $showCorrect = false)
    {
        $this->question    = $question;
        $this->disabled    = $disabled;
        $this->showCorrect = $showCorrect;

        if ( ! empty($value)) {
            // Load saved order
            $this->orderedOptions = collect($value)
                ->map(fn ($id) => $question->options->find($id))
                ->filter()
                ->values()
                ->toArray();
        } else {
            // Initial shuffled order
            $this->orderedOptions = $question->options->shuffle()->toArray();
        }
    }

    public function reorder($newOrder)
    {
        if ( ! $this->disabled) {
            $reordered = [];
            foreach ($newOrder as $index) {
                $reordered[] = $this->orderedOptions[$index];
            }
            $this->orderedOptions = $reordered;

            $ids = collect($this->orderedOptions)->pluck('id')->toArray();
            $this->dispatch('answerChanged', $ids);
        }
    }

    public function render()
    {
        return view('livewire.admin.pages.question-display.ordering');
    }
}
