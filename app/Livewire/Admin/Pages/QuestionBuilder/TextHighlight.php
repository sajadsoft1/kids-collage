<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\QuestionBuilder;

use Livewire\Component;

class TextHighlight extends Component
{
    public array $config = [];
    public ?int $questionIndex = null;

    public ?array $correct_answer = null;
    public string $templateText = '';

    public function mount(array $config = [], ?array $correct_answer = null, ?int $questionIndex = null): void
    {
        $this->config = array_merge($this->getDefaultConfig(), $config);
        $this->questionIndex = $questionIndex;
        if ($correct_answer !== null && isset($correct_answer['selections'])) {
            $this->correct_answer = $correct_answer;
        } else {
            // Initialize with default structure
            $this->correct_answer = [
                'selections' => [],
            ];
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
        $this->dispatchConfig();
        $this->dispatchCorrectAnswer();
    }

    protected function getDefaultConfig(): array
    {
        return [
            'min_selections' => 1,
            'max_selections' => null,
            'scoring_type' => 'partial',
        ];
    }

    public function extractOptionsFromTemplate(): void
    {
        if (empty($this->templateText)) {
            return;
        }

        // Extract placeholders like #text# from template
        preg_match_all('/#([^#]+)#/', $this->templateText, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches[0])) {
            return;
        }

        // Initialize correct_answer if needed
        if ($this->correct_answer === null) {
            $this->correct_answer = [
                'selections' => [],
            ];
        }

        // Clear existing selections
        $this->correct_answer['selections'] = [];

        // Build final text and calculate positions
        $finalText = '';
        $lastPosition = 0;
        $selections = [];

        foreach ($matches[0] as $index => $match) {
            $fullMatch = $match[0]; // Full match including #...#
            $matchOffset = $match[1]; // Position in template string
            $placeholderText = $matches[1][$index][0]; // Text inside #...#

            // Add text before this placeholder to final text (remove any # from it)
            $textBefore = mb_substr($this->templateText, $lastPosition, $matchOffset - $lastPosition);
            // Remove any # characters that might be in the text before
            $textBefore = str_replace('#', '', $textBefore);
            $finalText .= $textBefore;

            // Calculate start position in final text
            $start = mb_strlen($finalText);

            // Add placeholder text to final text
            $finalText .= $placeholderText;

            // Calculate end position
            $end = mb_strlen($finalText);

            // Store selection
            $selections[] = [
                'start' => $start,
                'end' => $end,
            ];

            // Update last position (skip the full placeholder including #)
            $lastPosition = $matchOffset + mb_strlen($fullMatch);
        }

        // Add remaining text after last placeholder (remove any # from it)
        $remainingText = mb_substr($this->templateText, $lastPosition);
        $remainingText = str_replace('#', '', $remainingText);
        $finalText .= $remainingText;

        // Store final text in config for later use
        $this->config['template_final_text'] = $finalText;

        // Store selections
        $this->correct_answer['selections'] = $selections;

        // Dispatch body update event to sync with question body
        if ($this->questionIndex === null) {
            $this->dispatch('bodyUpdated', ['body' => $finalText]);
        }

        $this->dispatchConfig();
        $this->dispatchCorrectAnswer();
    }

    public function getExtractedOptions(): array
    {
        if (empty($this->templateText)) {
            return [];
        }

        preg_match_all('/#([^#]+)#/', $this->templateText, $matches);

        return $matches[1] ?? [];
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

    protected function dispatchCorrectAnswer(): void
    {
        if ($this->correct_answer === null) {
            return;
        }

        if ($this->questionIndex !== null) {
            $this->dispatch('correctAnswerUpdated', [
                'index' => $this->questionIndex,
                'correct_answer' => $this->correct_answer,
            ]);
        } else {
            $this->dispatch('correctAnswerUpdated', [
                'correct_answer' => $this->correct_answer,
            ]);
        }
    }

    public function updatedCorrectAnswer(): void
    {
        $this->dispatchCorrectAnswer();
    }

    public function updatedConfig(): void
    {
        $this->dispatchConfig();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.admin.pages.question-builder.text-highlight');
    }
}
