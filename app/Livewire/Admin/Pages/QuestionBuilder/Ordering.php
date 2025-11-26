<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class Ordering extends Component
{
    public ?array $options = null;
    public array $config = [];
    public ?int $questionIndex = null;

    public function mount(?array $options = null, array $config = [], ?int $questionIndex = null): void
    {
        $this->options = (empty($options) || $options === null) ? $this->getDefaultOptions() : $options;
        $this->config = array_merge($this->getDefaultConfig(), $config);
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

    public function getScoringTypeOptions(): array
    {
        return [
            ['value' => 'exact', 'label' => 'دقیق (باید ترتیب کاملا درست باشد)'],
            ['value' => 'partial', 'label' => 'جزئی (بر اساس تعداد موقعیت‌های صحیح)'],
            ['value' => 'adjacent', 'label' => 'مجاورت (بر اساس جفت‌های مجاور صحیح)'],
        ];
    }

    public function addItem(): void
    {
        if ($this->options === null) {
            $this->options = $this->getDefaultOptions();
        }
        $this->options[] = [
            'content' => '',
            'order' => count($this->options) + 1,
        ];
        $this->dispatchOptions();
    }

    public function addOption(): void
    {
        $this->addItem();
    }

    public function removeItem(int $index): void
    {
        if ($this->options === null) {
            return;
        }
        if (count($this->options) > 2) {
            unset($this->options[$index]);
            $this->options = array_values($this->options);
            $this->reindexOrders();
            $this->dispatchOptions();
        }
    }

    public function removeOption(int $index): void
    {
        $this->removeItem($index);
    }

    public function moveUp(int $index): void
    {
        if ($this->options === null || $index <= 0) {
            return;
        }
        $this->swap($index, $index - 1);
    }

    public function moveDown(int $index): void
    {
        if ($this->options === null || $index >= count($this->options) - 1) {
            return;
        }
        $this->swap($index, $index + 1);
    }

    public function reorder(array $indices): void
    {
        if ($this->options === null || count($indices) !== 2) {
            return;
        }

        [$fromIndex, $toIndex] = $indices;

        // Validate indices
        if ($fromIndex < 0 || $fromIndex >= count($this->options) ||
            $toIndex < 0 || $toIndex >= count($this->options) ||
            $fromIndex === $toIndex) {
            return;
        }

        $this->swap($fromIndex, $toIndex);
    }

    protected function swap(int $a, int $b): void
    {
        if ($this->options === null) {
            return;
        }
        [$this->options[$a], $this->options[$b]] = [$this->options[$b], $this->options[$a]];
        $this->reindexOrders();
        $this->dispatchOptions();
    }

    protected function reindexOrders(): void
    {
        if ($this->options === null) {
            return;
        }
        foreach ($this->options as $i => $opt) {
            $this->options[$i]['order'] = $i + 1;
        }
    }

    protected function dispatchOptions(): void
    {
        if ($this->options === null) {
            return;
        }

        if ($this->questionIndex !== null) {
            $this->dispatch('optionsUpdated', [
                'index' => $this->questionIndex,
                'options' => $this->options,
            ]);
        } else {
            $this->dispatch('optionsUpdated', [
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
        } else {
            $this->dispatch('configUpdated', [
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
        return view('livewire.admin.pages.question-builder.ordering', [
            'scoringTypeOptions' => $this->getScoringTypeOptions(),
        ]);
    }
}
