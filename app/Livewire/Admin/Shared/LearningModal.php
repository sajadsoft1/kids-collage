<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Global learning/help modal – rendered in layout, opened via event from pages using HasLearningModal.
 */
class LearningModal extends Component
{
    public bool $showLearningModal = false;

    public string $learningModalTab = '';

    /**
     * Sections received from event. Shape: [key => ['title' => string, 'content' => string, 'icon' => string?]].
     *
     * @var array<int|string, array{title: string, content: string, icon?: string}>
     */
    public array $sections = [];

    #[On('open-learning-modal')]
    public function openFromEvent(array $sections = []): void
    {
        $this->sections = $sections;
        $firstKey = array_key_first($sections);

        $this->learningModalTab = $firstKey !== null ? (string) $firstKey : '';
        $this->showLearningModal = true;
    }

    public function closeLearningModal(): void
    {
        $this->showLearningModal = false;
        $this->learningModalTab = '';
        $this->sections = [];
    }

    public function render(): View
    {
        return view('livewire.admin.shared.learning-modal');
    }
}
