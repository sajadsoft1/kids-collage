<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class SingleChoice extends Component
{
    public array $options = [];
    public array $config  = [];

    public function mount(array $options = [], array $config = []): void
    {
        $this->options = empty($options) ? $this->getDefaultOptions() : $options;
        $this->config  = array_merge($this->getDefaultConfig(), $config);
    }

    protected function getDefaultOptions(): array
    {
        return [
            ['content' => '', 'is_correct' => false, 'order' => 1],
            ['content' => '', 'is_correct' => false, 'order' => 2],
        ];
    }

    protected function getDefaultConfig(): array
    {
        return [
            'shuffle_options'  => false,
            'show_explanation' => false,
        ];
    }

    public function addOption(): void
    {
        $this->options[] = [
            'content'    => '',
            'is_correct' => false,
            'order'      => count($this->options) + 1,
        ];
    }

    public function removeOption(int $index): void
    {
        if (count($this->options) > 2) {
            unset($this->options[$index]);
            $this->options = array_values($this->options);
        }
    }

    public function setCorrect(int $index): void
    {
        foreach ($this->options as $i => $opt) {
            $this->options[$i]['is_correct'] = $i === $index;
        }
    }

    public function updatedOptions(): void
    {
        $this->dispatch('optionsUpdated', $this->options);
    }

    public function updatedConfig(): void
    {
        $this->dispatch('configUpdated', $this->config);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-builder.single-choice');
    }
}
