<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class Ordering extends Component
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
            ['content' => '', 'order' => 1],
            ['content' => '', 'order' => 2],
            ['content' => '', 'order' => 3],
        ];
    }

    protected function getDefaultConfig()
    {
        return [
            'scoring_type' => 'exact',
        ];
    }

    public function addOption()
    {
        $this->options[] = [
            'content' => '',
            'order'   => count($this->options) + 1,
        ];
    }

    public function removeOption($index)
    {
        if (count($this->options) > 2) {
            unset($this->options[$index]);
            $this->options = array_values($this->options);

            // Re-order
            foreach ($this->options as $key => $option) {
                $this->options[$key]['order'] = $key + 1;
            }
        }
    }

    public function reorder($newOrder)
    {
        // $newOrder is array of indices
        $reordered = [];
        foreach ($newOrder as $index => $oldIndex) {
            $reordered[] = array_merge(
                $this->options[$oldIndex],
                ['order' => $index + 1]
            );
        }
        $this->options = $reordered;
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
        return view('livewire.admin.pages.question-builder.ordering');
    }
}
