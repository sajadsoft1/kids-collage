<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Exam;

use App\Models\Exam;
use App\Models\Question;
use App\Services\ExamService;
use Livewire\Component;
use Livewire\WithPagination;

class QuestionSelector extends Component
{
    use WithPagination;

    public Exam $exam;
    public $search = '';
    public $typeFilter = '';
    public $selectedQuestions = [];
    public $weights = [];

    public function mount(Exam $exam)
    {
        $this->exam = $exam;

        // Load existing questions
        $this->selectedQuestions = $exam->questions->pluck('id')->toArray();

        foreach ($exam->questions as $question) {
            $this->weights[$question->id] = $question->pivot->weight;
        }
    }

    public function toggleQuestion($questionId)
    {
        $question = Question::findOrFail($questionId);

        if (in_array($questionId, $this->selectedQuestions)) {
            // Remove
            app(ExamService::class)->detachQuestion($this->exam, $question);

            $this->selectedQuestions = array_values(
                array_diff($this->selectedQuestions, [$questionId])
            );
            unset($this->weights[$questionId]);
        } else {
            // Add
            $weight = $question->default_score;

            app(ExamService::class)->attachQuestion(
                $this->exam,
                $question,
                $weight
            );

            $this->selectedQuestions[] = $questionId;
            $this->weights[$questionId] = $weight;
        }

        $this->exam = $this->exam->fresh();

        $this->dispatch('questionsUpdated');
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
    }

    public function reorderQuestions($newOrder)
    {
        app(ExamService::class)->reorderQuestions($this->exam, $newOrder);

        $this->exam = $this->exam->fresh();

        session()->flash('success', 'ترتیب سوالات ذخیره شد');
    }

    public function render()
    {
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
