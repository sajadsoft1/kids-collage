<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Exam;

use App\Enums\AttemptStatusEnum;
use App\Enums\ExamStatusEnum;
use App\Enums\ExamTypeEnum;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('آزمون‌های من')]
class ExamListForUsers extends Component
{
    public string $activeTab = 'available';

    public string $search = '';

    /** Get available exams for the current user */
    #[Computed]
    public function availableExams(): \Illuminate\Support\Collection
    {
        $user = Auth::user();

        return Exam::query()
            ->where('status', ExamStatusEnum::PUBLISHED)
            ->where('type', ExamTypeEnum::SCORED)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->withCount('questions')
            ->with('category')
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('title', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(fn (Exam $exam) => $exam->canUserTakeExam($user))
            ->values();
    }

    /** Get completed exam attempts for the current user */
    #[Computed]
    public function completedAttempts(): \Illuminate\Support\Collection
    {
        $user = Auth::user();

        return ExamAttempt::query()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                AttemptStatusEnum::COMPLETED,
                AttemptStatusEnum::EXPIRED,
                AttemptStatusEnum::ABANDONED,
            ])
            ->with(['exam' => function ($q) {
                $q->withCount('questions');
            }])
            ->when($this->search, function ($q) {
                $q->whereHas('exam', function ($query) {
                    $query->where('title', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('completed_at', 'desc')
            ->get();
    }

    /** Get in-progress exam attempts for the current user */
    #[Computed]
    public function inProgressAttempts(): \Illuminate\Support\Collection
    {
        $user = Auth::user();

        return ExamAttempt::query()
            ->where('user_id', $user->id)
            ->where('status', AttemptStatusEnum::IN_PROGRESS)
            ->with(['exam' => function ($q) {
                $q->withCount('questions');
            }])
            ->withCount('answers')
            ->orderBy('started_at', 'desc')
            ->get();
    }

    /** Get statistics for the current user */
    #[Computed]
    public function stats(): array
    {
        $user = Auth::user();

        $completedAttempts = ExamAttempt::query()
            ->where('user_id', $user->id)
            ->where('status', AttemptStatusEnum::COMPLETED)
            ->get();

        return [
            'available_count' => $this->availableExams->count(),
            'completed_count' => $completedAttempts->count(),
            'in_progress_count' => $this->inProgressAttempts->count(),
            'average_score' => $completedAttempts->avg('percentage') ?? 0,
            'passed_count' => $completedAttempts->filter(fn ($a) => $a->isPassed())->count(),
        ];
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.pages.exam.exam-list-for-users');
    }
}
