<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class SingleChoice extends Component
{
    public $options = [];
    public $config  = [];

    public function mount($options = [], $config = [])
    {
        $this->options = empty($options) ? $this->getDefaultOptions() : $options;
        $this->config  = array_merge($this->getDefaultConfig(), $config);
    }

    protected function getDefaultOptions()
    {
        return [
            ['content' => '', 'is_correct' => false, 'order' => 1],
            ['content' => '', 'is_correct' => false, 'order' => 2],
            ['content' => '', 'is_correct' => false, 'order' => 3],
            ['content' => '', 'is_correct' => false, 'order' => 4],
        ];
    }

    protected function getDefaultConfig()
    {
        return [
            'shuffle_options'  => false,
            'show_explanation' => false,
        ];
    }

    public function addOption()
    {
        $this->options[] = [
            'content'    => '',
            'is_correct' => false,
            'order'      => count($this->options) + 1,
        ];
    }

    public function removeOption($index)
    {
        if (count($this->options) > 2) {
            unset($this->options[$index]);
            $this->options = array_values($this->options);
        }
    }

    public function setCorrect($index)
    {
        // تنها یک گزینه می‌تواند صحیح باشد
        foreach ($this->options as $key => $option) {
            $this->options[$key]['is_correct'] = ($key === $index);
        }
    }

    public function updatedOptions()
    {
        $this->dispatch('optionsUpdated', $this->options);
    }

    public function updatedConfig()
    {
        $this->dispatch('configUpdated', $this->config);
    }

    public function render()
    {
        return view('livewire.admin.pages.question-builder.single-choice');
    }
}
