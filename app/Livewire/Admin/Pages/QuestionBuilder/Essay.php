<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class Essay extends Component
{
    public array $config = [];
    public ?int $questionIndex = null;

    public function mount(array $config = [], ?int $questionIndex = null): void
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

    protected function getDefaultConfig(): array
    {
        return [
            'min_words' => 50,
            'max_words' => 1000,
            'rich_text' => true,
        ];
    }

    public function updatedConfig(): void
    {
        $this->dispatchConfig();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-builder.essay');
    }
}
