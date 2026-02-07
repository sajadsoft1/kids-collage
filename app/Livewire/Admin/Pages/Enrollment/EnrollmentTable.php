<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Enrollment;

use App\Helpers\PowerGridHelper;
use App\Models\Enrollment;
use App\Models\ExamAttempt;
use App\Traits\PowerGridHelperTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Mary\Traits\Toast;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use Throwable;

final class EnrollmentTable extends PowerGridComponent
{
    use PowerGridHelperTrait;
    use Toast;
    public string $tableName = 'index_enrollment_datatable';
    public string $sortDirection = 'desc';

    /** Modal: issue certificate – show summary and let admin set final grade. */
    public bool $showIssueCertificateModal = false;

    public ?int $issueCertificateEnrollmentId = null;

    public string $issueCertificateGrade = 'B';

    public function boot(): void
    {
        $this->fixedColumns = ['id', 'title', 'actions'];
    }

    protected function afterPowerGridSetUp(array &$setup): void
    {
        $setup[0] = $setup[0]->includeViewOnBottom('livewire.admin.pages.enrollment.partials.issue-certificate-modal');
    }

    #[Computed(persist: true)]
    public function breadcrumbs(): array
    {
        return [
            ['link' => route('admin.dashboard'), 'icon' => 's-home'],
            ['label' => trans('general.page.index.title', ['model' => trans('enrollment.model')])],
        ];
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            ['link' => route('admin.order.create'), 'icon' => 's-plus', 'label' => trans('general.page.create.title', ['model' => trans('enrollment.model')])],
        ];
    }

    public function datasource(): Builder
    {
        return Enrollment::query()
            ->with(['certificate', 'course.template']);
    }

    public function relationSearch(): array
    {
        return [
            'translations' => [
                'value',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('user_formated', fn ($row) => view('admin.datatable-shared.user-info', [
                'row' => $row->user,
            ]))
            ->add('course_formated', fn ($row) => view('admin.datatable-shared.course-info', [
                'row' => $row->course,
            ]))
            ->add('status_formated', fn ($row) => view('admin.datatable-shared.badge', [
                'value' => $row->status->title(),
                'color' => $row->status->color(),
            ]))
            ->add('created_at_formatted', fn ($row) => PowerGridHelper::fieldCreatedAtFormated($row));
    }

    public function columns(): array
    {
        return [
            PowerGridHelper::columnId(),
            Column::make(trans('validation.attributes.username'), 'user_formated', 'user_id'),
            Column::make(trans('validation.attributes.course_id'), 'course_formated', 'course_id'),
            Column::make(trans('validation.attributes.status'), 'status_formated', 'status')->sortable(),
            PowerGridHelper::columnCreatedAT(),
            PowerGridHelper::columnAction(),
        ];
    }

    public function actions(Enrollment $row): array
    {
        $actions = [];

        if ($row->hasCertificate()) {
            $certificate = $row->certificate;
            $actions[] = Button::add('verify')
                ->slot('<i class="fa fa-check-circle text-info"></i>')
                ->attributes(['class' => 'btn btn-square md:btn-sm btn-xs'])
                ->route('certificates.verify', ['id' => $certificate->id, 'hash' => $certificate->signature_hash], '_blank')
                ->tooltip(trans('certificateTemplate.verification'));
            $actions[] = Button::add('download')
                ->slot('<i class="fa fa-download text-success"></i>')
                ->attributes(['class' => 'btn btn-square md:btn-sm btn-xs'])
                ->route('certificates.download', ['id' => $certificate->id, 'hash' => $certificate->signature_hash], '_blank')
                ->tooltip(trans('general.download') ?: 'Download');
        } elseif ($row->canIssueCertificate()) {
            $actions[] = Button::add('issue-certificate')
                ->slot('<i class="fa fa-certificate text-warning"></i>')
                ->attributes(['class' => 'btn btn-square md:btn-sm btn-xs'])
                ->dispatch('open-issue-certificate-modal', ['enrollmentId' => $row->id])
                ->tooltip(trans('enrollment.issue_certificate'));
        }

        return $actions;
    }

    #[On('open-issue-certificate-modal')]
    public function openIssueCertificateModal(int $enrollmentId): void
    {
        $enrollment = Enrollment::query()
            ->with(['user', 'course.template'])
            ->find($enrollmentId);

        if ( ! $enrollment) {
            $this->error(trans('enrollment.not_found'));

            return;
        }

        if ( ! $enrollment->canIssueCertificate()) {
            $this->error(trans('enrollment.cannot_issue_certificate'));

            return;
        }

        $courseLinkedAttempts = $this->getCourseLinkedExamAttempts($enrollment);
        $this->issueCertificateEnrollmentId = $enrollmentId;
        $this->issueCertificateGrade = $enrollment->getSuggestedGrade($courseLinkedAttempts);
        $this->showIssueCertificateModal = true;
    }

    /** Completed exam attempts for this enrollment's user where the exam is linked to the enrollment's course (rules: enrolled_in_course). */
    protected function getCourseLinkedExamAttempts(Enrollment $enrollment): \Illuminate\Support\Collection
    {
        $attempts = ExamAttempt::query()
            ->byUser($enrollment->user_id)
            ->completed()
            ->whereHas('exam', function ($query) use ($enrollment) {
                $query->whereJsonContains('extra_attributes->rules->groups->conditions->value', $enrollment->course_id)
                    ->whereJsonContains('extra_attributes->rules->groups->conditions->operator', 'equals');
            })
            ->orderByDesc('completed_at')
            ->limit(50)
            ->get();

        return $attempts;
    }

    public function closeIssueCertificateModal(): void
    {
        $this->showIssueCertificateModal = false;
        $this->issueCertificateEnrollmentId = null;
        $this->issueCertificateGrade = 'B';
    }

    public function confirmIssueCertificate(): void
    {
        $validGrades = ['A', 'B', 'C', 'D', 'F'];
        if ( ! in_array($this->issueCertificateGrade, $validGrades, true)) {
            $this->error(trans('enrollment.invalid_grade'));

            return;
        }

        $enrollment = Enrollment::find($this->issueCertificateEnrollmentId);
        if ( ! $enrollment || ! $enrollment->canIssueCertificate()) {
            $this->error(trans('enrollment.cannot_issue_certificate'));
            $this->closeIssueCertificateModal();

            return;
        }

        try {
            $enrollment->issueCertificate($this->issueCertificateGrade);
            $this->success(trans('enrollment.certificate_issued_success'));
            $this->closeIssueCertificateModal();
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            $this->error($e->getMessage());
        }
    }

    /** Enrollment for the issue-certificate modal (with user, course). */
    #[Computed]
    public function issueCertificateEnrollment(): ?Enrollment
    {
        if ($this->issueCertificateEnrollmentId === null) {
            return null;
        }

        return Enrollment::query()
            ->with(['user', 'course.template'])
            ->find($this->issueCertificateEnrollmentId);
    }

    /** All completed exam attempts for the enrollment's user (shown in modal). Course-linked ones are used for suggested grade. */
    #[Computed]
    public function issueCertificateExamAttempts(): \Illuminate\Support\Collection
    {
        $enrollment = $this->issueCertificateEnrollment;
        if ( ! $enrollment) {
            return collect();
        }

        return ExamAttempt::query()
            ->byUser($enrollment->user_id)
            ->completed()
            ->with('exam')
            ->orderByDesc('completed_at')
            ->limit(20)
            ->get();
    }

    /** Whether the given attempt's exam is linked to this enrollment's course (rules: enrolled_in_course). */
    public function isAttemptLinkedToCourse(ExamAttempt $attempt, Enrollment $enrollment): bool
    {
        return $attempt->exam && $attempt->exam->isForCourse($enrollment->course_id);
    }

    public function filters(): array
    {
        return [
            PowerGridHelper::filterDatepickerJalali('created_at_formatted', 'created_at', [
                'maxDate' => now()->format('Y-m-d'),
            ]),
        ];
    }

    public function noDataLabel(): string|View
    {
        return view('admin.datatable-shared.empty-table', [
            'link' => route('admin.order.create'),
        ]);
    }
}
