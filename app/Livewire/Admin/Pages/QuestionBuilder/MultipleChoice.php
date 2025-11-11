<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class MultipleChoice extends Component
{
    public array $options = [];
    public array $config = [];
    public ?int $questionIndex = null;
    public bool $hasCorrectAnswer = true;

    public function mount(array $options = [], array $config = [], ?int $questionIndex = null, ?bool $hasCorrectAnswer = null): void
    {
        $mergedConfig = array_merge($this->getDefaultConfig(), $config);
        $this->options = empty($options) ? $this->getDefaultOptions() : $options;
        $this->config = $mergedConfig;
        $this->hasCorrectAnswer = $hasCorrectAnswer ?? (bool) ($mergedConfig['has_correct_answer'] ?? true);
        $this->questionIndex = $questionIndex;

        if ( ! $this->hasCorrectAnswer) {
            $this->clearCorrectFlags();
        }

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
            ['content' => '', 'is_correct' => false, 'order' => 3],
            ['content' => '', 'is_correct' => false, 'order' => 4],
        ];
    }

    protected function getDefaultConfig(): array
    {
        return [
            'shuffle_options' => false,
            'scoring_type' => 'all_or_nothing',
            'has_correct_answer' => true,
        ];
    }

    public function addOption(): void
    {
        $this->options[] = [
            'content' => '',
            'is_correct' => false,
            'order' => count($this->options) + 1,
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

    public function toggleCorrect(int $index): void
    {
        if ( ! $this->hasCorrectAnswer) {
            return;
        }

        $this->options[$index]['is_correct'] = ! $this->options[$index]['is_correct'];
        $this->dispatchOptions();
    }

    public function updatedHasCorrectAnswer(): void
    {
        if ( ! $this->hasCorrectAnswer) {
            $this->clearCorrectFlags();
            $this->dispatchOptions();
        }

        $this->dispatchConfig();
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
            $this->config['has_correct_answer'] = $this->hasCorrectAnswer;

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
        $this->config['has_correct_answer'] = $this->hasCorrectAnswer;
        $this->dispatchConfig();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-builder.multiple-choice');
    }

    protected function clearCorrectFlags(): void
    {
        foreach ($this->options as $i => $option) {
            $this->options[$i]['is_correct'] = false;
        }
    }
}
