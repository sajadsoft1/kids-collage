<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class TrueFalse extends Component
{
    public array $config = [];
    public ?int $questionIndex = null;

    public function mount(array $config = [], ?array $correct_answer = null, ?int $questionIndex = null): void
    {
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
        $this->dispatchConfig();
    }

    protected function getDefaultConfig(): array
    {
        return [
            'true_label' => 'درست',
            'false_label' => 'غلط',
        ];
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

    public function updatedConfig(): void
    {
        $this->dispatchConfig();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-builder.true-false');
    }
}
