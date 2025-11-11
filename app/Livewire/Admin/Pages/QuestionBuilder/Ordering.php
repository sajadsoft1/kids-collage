<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class Ordering extends Component
{
    public array $options       = [];
    public array $config        = [];
    public ?int $questionIndex  = null;

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
            ['content' => 'مورد ۱', 'order' => 1],
            ['content' => 'مورد ۲', 'order' => 2],
            ['content' => 'مورد ۳', 'order' => 3],
        ];
    }

    protected function getDefaultConfig(): array
    {
        return [
            'scoring_type' => 'exact',
        ];
    }

    public function addItem(): void
    {
        $this->options[] = [
            'content' => '',
            'order' => count($this->options) + 1,
        ];
        $this->dispatchOptions();
    }

    public function removeItem(int $index): void
    {
        if (count($this->options) > 2) {
            unset($this->options[$index]);
            $this->options = array_values($this->options);
            $this->reindexOrders();
            $this->dispatchOptions();
        }
    }

    public function moveUp(int $index): void
    {
        if ($index <= 0) {
            return;
        }
        $this->swap($index, $index - 1);
    }

    public function moveDown(int $index): void
    {
        if ($index >= count($this->options) - 1) {
            return;
        }
        $this->swap($index, $index + 1);
    }

    protected function swap(int $a, int $b): void
    {
        [$this->options[$a], $this->options[$b]] = [$this->options[$b], $this->options[$a]];
        $this->reindexOrders();
        $this->dispatchOptions();
    }

    protected function reindexOrders(): void
    {
        foreach ($this->options as $i => $opt) {
            $this->options[$i]['order'] = $i + 1;
        }
    }

    protected function dispatchOptions(): void
    {
        if ($this->questionIndex !== null) {
            $this->dispatch('optionsUpdated', [
                'index' => $this->questionIndex,
                'options' => $this->options,
            ]);
        }
    }

    protected function dispatchConfig(): void
    {
        if ($this->questionIndex !== null) {
            $this->dispatch('configUpdated', [
                'index' => $this->questionIndex,
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
        return view('livewire.admin.pages.question-builder.ordering');
    }
}
