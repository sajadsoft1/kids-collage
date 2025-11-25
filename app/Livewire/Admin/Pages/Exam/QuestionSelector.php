<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Exam;

use App\Enums\ExamTypeEnum;
use App\Models\Exam;
use App\Models\Question;
use App\Services\ExamService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class QuestionSelector extends Component
{
    use WithPagination;

    public Exam $exam;
    public ?float $totalScore = null;
    public $search = '';
    public $typeFilter = '';
    public $selectedQuestions = [];
    public $weights = [];

    public function mount(Exam $exam, ?float $totalScore = null)
    {
        $this->exam = $exam;
        $this->totalScore = $totalScore ?? $exam->total_score;

        // Load existing questions
        $this->selectedQuestions = $exam->questions->pluck('id')->toArray();

        foreach ($exam->questions as $question) {
            $this->weights[$question->id] = $question->pivot->weight;
        }
    }

    public function handleQuestionSelection($questionId, $checked): void
    {
        $question = Question::findOrFail($questionId);

        if ($checked) {
            // Add question when checkbox is checked
            if ( ! in_array($questionId, $this->selectedQuestions)) {
                $weight = $question->default_score;

                app(ExamService::class)->attachQuestion(
                    $this->exam,
                    $question,
                    $weight
                );

                $this->selectedQuestions[] = $questionId;
                $this->weights[$questionId] = $weight;
            }
        } else {
            // Remove question when checkbox is unchecked
            if (in_array($questionId, $this->selectedQuestions)) {
                app(ExamService::class)->detachQuestion($this->exam, $question);

                $this->selectedQuestions = array_values(
                    array_diff($this->selectedQuestions, [$questionId])
                );
                unset($this->weights[$questionId]);
            }
        }

        $this->exam = $this->exam->fresh();

        $this->dispatch('questionsUpdated');
        $this->dispatchValidationState();
    }

    public function removeQuestion($questionId): void
    {
        $question = Question::findOrFail($questionId);

        if (in_array($questionId, $this->selectedQuestions)) {
            app(ExamService::class)->detachQuestion($this->exam, $question);

            $this->selectedQuestions = array_values(
                array_diff($this->selectedQuestions, [$questionId])
            );
            unset($this->weights[$questionId]);

            $this->exam = $this->exam->fresh();

            $this->dispatch('questionsUpdated');
            $this->dispatchValidationState();
        }
    }

    public function updateWeight($questionId, $weight)
    {
        $question = Question::findOrFail($questionId);

        app(ExamService::class)->updateQuestionWeight(
            $this->exam,
            $question,
            (float) $weight
        );

        $this->weights[$questionId] = $weight;

        $this->exam = $this->exam->fresh();
        $this->dispatchValidationState();
    }

    public function reorderQuestions($newOrder)
    {
        app(ExamService::class)->reorderQuestions($this->exam, $newOrder);

        $this->exam = $this->exam->fresh();

        session()->flash('success', 'ترتیب سوالات ذخیره شد');
    }

    public function reorderSelectedQuestions(array $orderedIds): void
    {
        app(ExamService::class)->reorderQuestions($this->exam, $orderedIds);

        $this->exam = $this->exam->fresh();
        $this->dispatchValidationState();
    }

    public function moveQuestion(int $questionId, string $direction): void
    {
        $orderedIds = $this->exam->questions()
            ->orderBy('exam_question.order')
            ->pluck('questions.id')
            ->toArray();

        $currentPosition = array_search($questionId, $orderedIds, true);

        if ($currentPosition === false) {
            return;
        }

        if ($direction === 'up' && $currentPosition === 0) {
            return;
        }

        if ($direction === 'down' && $currentPosition === count($orderedIds) - 1) {
            return;
        }

        $swapWith = $direction === 'up' ? $currentPosition - 1 : $currentPosition + 1;
        [$orderedIds[$currentPosition], $orderedIds[$swapWith]] = [$orderedIds[$swapWith], $orderedIds[$currentPosition]];

        app(ExamService::class)->reorderQuestions($this->exam, $orderedIds);

        $this->exam = $this->exam->fresh();
        $this->dispatchValidationState();
    }

    #[On('totalScoreUpdated')]
    public function handleTotalScoreUpdated(?float $totalScore = null): void
    {
        // Update totalScore when event is received from parent
        if ($totalScore !== null) {
            $this->totalScore = $totalScore;
            $this->exam->total_score = $totalScore;
            $this->dispatchValidationState();
        }
    }

    public function updatedTotalScore(): void
    {
        // When totalScore prop changes from parent, update exam model
        if ($this->totalScore !== null) {
            $this->exam->total_score = $this->totalScore;
        }
    }

    protected function validateTotalWeight(): bool
    {
        if ($this->exam->type !== ExamTypeEnum::SCORED) {
            return true;
        }

        if ( ! $this->totalScore) {
            return false;
        }

        $totalWeight = $this->exam->questions()->sum('exam_question.weight');

        return abs($totalWeight - $this->totalScore) < 0.01;
    }

    protected function dispatchValidationState(): void
    {
        $isValid = $this->validateTotalWeight();
        $this->dispatch('questionSelectorValidationChanged', isValid: $isValid);
    }

    public function render()
    {
        // Always refresh exam to get latest data, but preserve totalScore from prop
        $this->exam = $this->exam->fresh();
        if ($this->totalScore !== null) {
            $this->exam->total_score = $this->totalScore;
        }

        // Dispatch validation state to parent
        $this->dispatchValidationState();

        $query = Question::query()
            ->active()
            ->with(['category', 'subject', 'options'])
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->when($this->typeFilter, fn ($q) => $q->byType($this->typeFilter))
            ->latest();

        return view('livewire.admin.pages.exam.question-selector', [
            'questions' => $query->paginate(10),
            'currentQuestions' => $this->exam->questions,
        ]);
    }
}
