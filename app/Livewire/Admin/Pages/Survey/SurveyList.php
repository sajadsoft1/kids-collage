<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Survey;

use App\Enums\AttemptStatusEnum;
use App\Enums\ExamTypeEnum;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class SurveyList extends Component
{
    use Toast, WithPagination;

    public string $viewMode = 'accessible';

    protected $queryString = [
        'page'     => ['except' => 1],
        'viewMode' => ['except' => 'accessible'],
    ];

    public function updatedViewMode(string $value): void
    {
        if ( ! array_key_exists($value, $this->viewModeOptions())) {
            $this->viewMode = 'accessible';
        }

        $this->resetPage();
    }

    public function edit(?int $examId = null): void
    {
        $this->redirect(route('admin.survey.edit', [
            'exam' => $examId,
        ]), true);
    }

    public function create(): void
    {
        $this->redirect(route('admin.survey.create'), true);
    }

    public function results(int $examId): void
    {
        $this->redirect(route('admin.survey.results', [
            'exam' => $examId,
        ]), true);
    }

    public function take(int $examId): void
    {
        $user = Auth::user();
        abort_if($user === null, 403);

        $exam = Exam::query()
            ->where('type', ExamTypeEnum::SURVEY)
            ->findOrFail($examId);

        $inProgressAttempt = $exam->getUserInProgressAttempt($user);

        if ( ! $exam->canUserTakeExam($user) && $inProgressAttempt === null) {
            $this->warning('شما دسترسی لازم برای شرکت در این نظر سنجی را ندارید.');

            return;
        }

        $this->redirect(url("/admin/survey/{$examId}/take"), true);
    }

    public function render(): View
    {
        $user = Auth::user();
        abort_if($user === null, 403);

        $query = Exam::query()
            ->where('type', ExamTypeEnum::SURVEY)
            ->with(['attempts' => function ($attemptsQuery) use ($user) {
                $attemptsQuery->where('user_id', $user->id)
                    ->orderByDesc('updated_at')
                    ->limit(1);
            }])
            ->withCount('questions')
            ->orderByDesc('starts_at')
            ->orderByDesc('created_at');

        if ($this->viewMode === 'created') {
            $query->where('created_by', $user->id);
        } elseif ($this->viewMode === 'accessible') {
            $surveyIds = $this->resolveAccessibleSurveyIds($user);

            if (empty($surveyIds)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('id', $surveyIds);
            }
        }

        $surveys = $query->paginate(15);

        $surveys = $this->decorateSurveys($surveys, $user);

        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('_menu.survey_management')],
        ];

        $canCreateSurvey    = $user->can('create', Exam::class);
        $breadcrumbsActions = $canCreateSurvey
            ? [['link' => route('admin.survey.create'), 'icon' => 's-plus', 'label' => 'ایجاد نظر سنجی جدید']]
            : [];

        return view('livewire.admin.pages.survey.survey-list', [
            'surveys'            => $surveys,
            'breadcrumbs'        => $breadcrumbs,
            'breadcrumbsActions' => $breadcrumbsActions,
            'viewModeOptions'    => $this->viewModeOptions(),
            'canCreateSurvey'    => $canCreateSurvey,
        ]);
    }

    protected function decorateSurveys(LengthAwarePaginator $surveys, User $user): LengthAwarePaginator
    {
        $surveys->getCollection()->transform(function (Exam $survey) use ($user) {
            $survey->setAttribute('user_state', $this->determineUserState($survey, $user));

            return $survey;
        });

        return $surveys;
    }

    protected function determineUserState(Exam $survey, User $user): array
    {
        $attempt = $survey->relationLoaded('attempts')
            ? $survey->attempts->first()
            : null;

        $attemptStatus = $attempt?->status;

        if ($attemptStatus !== null && ! $attemptStatus instanceof AttemptStatusEnum) {
            $attemptStatus = AttemptStatusEnum::tryFrom((string) $attemptStatus);
        }

        if ($attemptStatus === AttemptStatusEnum::IN_PROGRESS) {
            return [
                'code'           => 'in_progress',
                'label'          => 'در حال انجام',
                'badge_class'    => 'bg-warning/20 text-warning',
                'action_label'   => 'ادامه',
                'action_enabled' => true,
                'helper'         => 'می‌توانید پاسخ‌گویی به این نظر سنجی را ادامه دهید.',
            ];
        }

        $canTake = $survey->canUserTakeExam($user);

        if ($canTake) {
            $retake = in_array($attemptStatus, [AttemptStatusEnum::COMPLETED, AttemptStatusEnum::ABANDONED], true);

            return [
                'code'           => 'available',
                'label'          => $retake ? 'قابل شروع مجدد' : 'قابل شروع',
                'badge_class'    => 'bg-primary/10 text-primary',
                'action_label'   => $retake ? 'شروع مجدد' : 'شروع',
                'action_enabled' => true,
                'helper'         => $retake
                    ? 'می‌توانید پاسخ‌های جدیدی برای این نظر سنجی ثبت کنید.'
                    : 'این نظر سنجی برای شما فعال است.',
            ];
        }

        if ($attemptStatus === AttemptStatusEnum::COMPLETED) {
            return [
                'code'           => 'completed',
                'label'          => 'تکمیل شده',
                'badge_class'    => 'bg-success/15 text-success',
                'action_label'   => null,
                'action_enabled' => false,
                'helper'         => 'پاسخ‌های شما ثبت شده است.',
            ];
        }

        if ($attemptStatus === AttemptStatusEnum::EXPIRED) {
            return [
                'code'           => 'expired',
                'label'          => 'منقضی شده',
                'badge_class'    => 'bg-error/15 text-error',
                'action_label'   => null,
                'action_enabled' => false,
                'helper'         => 'مهلت پاسخ‌دهی به این نظر سنجی به پایان رسیده است.',
            ];
        }

        if ($attemptStatus === AttemptStatusEnum::ABANDONED) {
            return [
                'code'           => 'abandoned',
                'label'          => 'رها شده',
                'badge_class'    => 'bg-warning/15 text-warning',
                'action_label'   => null,
                'action_enabled' => false,
                'helper'         => 'پاسخ‌گویی نیمه‌کاره رها شده است.',
            ];
        }

        return [
            'code'           => 'locked',
            'label'          => 'غیرفعال',
            'badge_class'    => 'bg-base-200 text-base-content/70',
            'action_label'   => null,
            'action_enabled' => false,
            'helper'         => 'در حال حاضر امکان شرکت در این نظر سنجی را ندارید.',
        ];
    }

    protected function resolveAccessibleSurveyIds(User $user): array
    {
        $accessible = Exam::query()
            ->where('type', ExamTypeEnum::SURVEY)
            ->get()
            ->filter(fn (Exam $exam) => $exam->canUserTakeExam($user))
            ->pluck('id')
            ->all();

        $attempted = ExamAttempt::query()
            ->where('user_id', $user->id)
            ->whereHas('exam', fn ($query) => $query->where('type', ExamTypeEnum::SURVEY))
            ->pluck('exam_id')
            ->all();

        $created = Exam::query()
            ->where('type', ExamTypeEnum::SURVEY)
            ->where('created_by', $user->id)
            ->pluck('id')
            ->all();

        return array_values(array_unique(array_merge($accessible, $attempted, $created)));
    }

    protected function viewModeOptions(): array
    {
        return [
            'accessible' => 'نظر سنجی‌های مربوط به من',
            'created'    => 'نظر سنجی‌های ساخته شده توسط من',
            'all'        => 'همه نظر سنجی‌ها',
        ];
    }
}
