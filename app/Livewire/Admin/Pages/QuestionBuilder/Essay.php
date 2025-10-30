<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class Essay extends Component
{
    public array $config = [];

    public function mount(array $config = []): void
    {
        $this->config = array_merge($this->getDefaultConfig(), $config);
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
        $this->dispatch('configUpdated', $this->config);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-builder.essay');
    }
}
