<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Survey;

use App\Enums\AttemptStatusEnum;
use App\Enums\ExamTypeEnum;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\UserAnswer;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class SurveyResults extends Component
{
    use Toast;

    public Exam $exam;

    public array $summary = [];

    public array $questionBreakdown = [];

    public array $recentAttempts = [];

    public function mount(Exam $exam): void
    {
        abort_if($exam->type !== ExamTypeEnum::SURVEY, 404);

        $this->exam = $exam;
        $this->exam->loadMissing(['questions.options']);

        $this->prepareResults();
    }

    public function render(): View
    {
        return view('livewire.admin.pages.survey.survey-results', [
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => route('admin.survey.index'), 'label' => trans('_menu.survey_management')],
                ['label' => 'نتایج نظر سنجی'],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.survey.index'), 'icon' => 's-arrow-left'],
            ],
            'summary' => $this->summary,
            'questionBreakdown' => $this->questionBreakdown,
            'recentAttempts' => $this->recentAttempts,
            'exam' => $this->exam,
        ]);
    }

    protected function prepareResults(): void
    {
        $attempts = $this->exam->attempts()
            ->with([
                'user',
                'answers' => fn ($query) => $query->select([
                    'id',
                    'exam_attempt_id',
                    'question_id',
                    'answer_data',
                    'answered_at',
                ]),
            ])
            ->latest()
            ->get();

        $totalAttempts = $attempts->count();
        $completedAttempts = $attempts->where('status', AttemptStatusEnum::COMPLETED);
        $inProgressAttempts = $attempts->where('status', AttemptStatusEnum::IN_PROGRESS);
        $abandonedAttempts = $attempts->whereIn('status', [
            AttemptStatusEnum::ABANDONED,
            AttemptStatusEnum::EXPIRED,
        ]);

        $uniqueParticipants = $attempts->pluck('user_id')->filter()->unique()->count();
        $totalResponses = $attempts->flatMap->answers->count();
        $avgQuestionsAnswered = $totalAttempts > 0
            ? round($totalResponses / $totalAttempts, 1)
            : 0;

        $averageCompletionSeconds = $completedAttempts->count() > 0
            ? (int) round($completedAttempts->map->getElapsedTime()->avg())
            : 0;

        $this->summary = [
            'total_attempts' => $totalAttempts,
            'unique_participants' => $uniqueParticipants,
            'completed_attempts' => $completedAttempts->count(),
            'in_progress_attempts' => $inProgressAttempts->count(),
            'abandoned_attempts' => $abandonedAttempts->count(),
            'completion_rate' => $totalAttempts > 0
                ? round(($completedAttempts->count() / $totalAttempts) * 100, 1)
                : 0,
            'average_completion_time' => $this->formatDuration($averageCompletionSeconds),
            'average_questions' => $avgQuestionsAnswered,
            'last_response_at' => optional($attempts->flatMap->answers->sortByDesc('answered_at')->first()?->answered_at)
                ?->format('Y-m-d H:i'),
        ];

        $answersGrouped = $attempts->flatMap->answers->groupBy('question_id');
        $attemptsCount = max($totalAttempts, 1);

        $this->questionBreakdown = $this->exam->questions->map(function ($question) use (
            $answersGrouped,
            $attemptsCount
        ) {
            /** @var \Illuminate\Support\Collection $responses */
            $responses = $answersGrouped->get($question->id, collect());
            $responsesCount = $responses->count();

            $optionStats = $question->options->map(function ($option) use ($responses, $responsesCount) {
                $count = $responses->filter(function (UserAnswer $answer) use ($option) {
                    $values = $this->normalizeAnswerData($answer->answer_data);

                    return in_array($option->id, $values, false);
                })->count();

                return [
                    'id' => $option->id,
                    'content' => $option->content,
                    'selections' => $count,
                    'ratio' => $responsesCount > 0
                        ? round(($count / $responsesCount) * 100, 1)
                        : 0,
                    'is_correct' => (bool) $option->is_correct,
                ];
            })->sortByDesc('selections')->values()->all();

            return [
                'id' => $question->id,
                'title' => $question->title,
                'body' => $question->body,
                'type' => $question->type->value,
                'responses_count' => $responsesCount,
                'skipped_count' => max(0, $attemptsCount - $responsesCount),
                'options' => $optionStats,
                'most_selected' => $optionStats[0] ?? null,
                'show_explanation' => $question->config['show_explanation'] ?? false,
                'explanation' => $question->explanation,
            ];
        })->toArray();

        $this->recentAttempts = $completedAttempts
            ->sortByDesc('completed_at')
            ->take(10)
            ->map(function (ExamAttempt $attempt) {
                $user = $attempt->user;

                return [
                    'participant' => $user?->full_name
                        ?? $user?->name
                        ?? ($user?->email ? explode('@', $user->email)[0] : 'کاربر ناشناس'),
                    'completed_at' => optional($attempt->completed_at)?->format('Y-m-d H:i'),
                    'questions_answered' => $attempt->answers->count(),
                    'progress' => $attempt->getProgressPercentage(),
                ];
            })
            ->values()
            ->toArray();
    }

    /** @return array<int,int> */
    protected function normalizeAnswerData(mixed $value): array
    {
        if (is_array($value)) {
            return array_map(fn ($item) => (int) $item, $value);
        }

        if (is_null($value) || $value === '') {
            return [];
        }

        return [(int) $value];
    }

    protected function formatDuration(int $seconds): string
    {
        if ($seconds <= 0) {
            return '-';
        }

        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
        }

        return sprintf('%02d:%02d', $minutes, $secs);
    }
}
