<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class SingleChoice extends Component
{
    public array $options      = [];
    public array $config       = [];
    public ?int $questionIndex = null;

    public function mount(array $options = [], array $config = [], ?int $questionIndex = null): void
    {
        $this->options       = empty($options) ? $this->getDefaultOptions() : $options;
        $this->config        = array_merge($this->getDefaultConfig(), $config);
        $this->questionIndex = $questionIndex;
        // Sync initial data
        $this->syncData();
    }

    public function dehydrate(): void
    {
        // Sync data before component dehydrates (sends to frontend)
        $this->syncData();
    }

    protected function syncData(): void
    {
        $this->dispatchOptions();
        $this->dispatchConfig();
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
        $this->dispatchOptions();
    }

    public function removeOption(int $index): void
    {
        if (count($this->options) > 2) {
            unset($this->options[$index]);
            $this->options = array_values($this->options);
            $this->dispatchOptions();
        }
    }

    public function setCorrect(int $index): void
    {
        foreach ($this->options as $i => $opt) {
            $this->options[$i]['is_correct'] = $i === $index;
        }
        $this->dispatchOptions();
    }

    protected function dispatchOptions(): void
    {
        if ($this->questionIndex !== null) {
            $this->dispatch('optionsUpdated', [
                'index'   => $this->questionIndex,
                'options' => $this->options,
            ]);
        }
    }

    protected function dispatchConfig(): void
    {
        if ($this->questionIndex !== null) {
            $this->dispatch('configUpdated', [
                'index'  => $this->questionIndex,
                'config' => $this->config,
            ]);
        }
    }

    public function updatedOptions(): void
    {
        $this->dispatchOptions();
    }

    public function updatedConfig(): void
    {
        $this->dispatchConfig();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-builder.single-choice');
    }
}
