<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionDisplay;

use App\Models\Question;
use Livewire\Component;

class TextHighlight extends Component
{
    public Question $question;

    /** @var array{selections: array<int,array{start:int,end:int}>}|null */
    public ?array $value = ['selections' => []];

    public bool $disabled = false;

    public bool $showCorrect = false;

    public function mount(Question $question, ?array $value = null, bool $disabled = false, bool $showCorrect = false): void
    {
        $this->question = $question;
        $this->value = $value ?? ['selections' => []];
        $this->disabled = $disabled;
        $this->showCorrect = $showCorrect;
    }

    protected function ensureValue(): void
    {
        if ($this->value === null) {
            $this->value = ['selections' => []];
        }
    }

    public function addSelection(int $start, int $end): void
    {
        if ($this->disabled) {
            return;
        }

        $config = $this->question->config ?? [];
        $maxSelections = $config['max_selections'] ?? null;
        $this->ensureValue();
        $currentCount = count($this->value['selections'] ?? []);

        // Check max selections limit
        if ($maxSelections !== null && $currentCount >= $maxSelections) {
            return;
        }

        // Check if this selection already exists
        foreach ($this->value['selections'] as $existing) {
            if ($existing['start'] === $start && $existing['end'] === $end) {
                return; // Already selected
            }
        }

        $this->value['selections'][] = ['start' => $start, 'end' => $end];
        $this->dispatch('answerChanged', $this->value);
    }

    public function removeSelection(int $index): void
    {
        if ($this->disabled) {
            return;
        }

        $this->ensureValue();

        if (isset($this->value['selections'][$index])) {
            unset($this->value['selections'][$index]);
            $this->value['selections'] = array_values($this->value['selections']);
            $this->dispatch('answerChanged', $this->value);
        }
    }

    public function getConfig(): array
    {
        return $this->question->config ?? [];
    }

    public function getMinSelections(): int
    {
        return $this->getConfig()['min_selections'] ?? 1;
    }

    public function getMaxSelections(): ?int
    {
        $max = $this->getConfig()['max_selections'] ?? null;

        return $max !== null ? (int) $max : null;
    }

    public function getCurrentCount(): int
    {
        $this->ensureValue();

        return count($this->value['selections'] ?? []);
    }

    public function canSelectMore(): bool
    {
        $max = $this->getMaxSelections();
        if ($max === null) {
            return true;
        }

        return $this->getCurrentCount() < $max;
    }

    public function hasMinimumSelections(): bool
    {
        return $this->getCurrentCount() >= $this->getMinSelections();
    }

    public function clearSelections(): void
    {
        if ($this->disabled) {
            return;
        }
        $this->value['selections'] = [];
        $this->dispatch('answerChanged', $this->value);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-display.text-highlight');
    }
}
