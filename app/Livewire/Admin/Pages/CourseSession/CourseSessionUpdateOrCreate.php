<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\CourseSession;

use App\Actions\CourseSession\StoreCourseSessionAction;
use App\Actions\CourseSession\UpdateCourseSessionAction;
use App\Enums\SessionStatus;
use App\Enums\SessionType;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\CourseSessionTemplate;
use App\Models\CourseTemplate;
use App\Models\Room;
use App\Traits\CrudHelperTrait;
use Illuminate\View\View;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

class CourseSessionUpdateOrCreate extends Component
{
    use CrudHelperTrait;
    use Toast;

    public CourseTemplate $courseTemplate;
    public Course $course;
    public CourseSession $model;
    public int $course_session_template_id = 0;
    public ?string $date = null;
    public ?string $start_time = null;
    public ?string $end_time = null;
    public ?int $room_id = null;
    public ?string $meeting_link = null;
    public ?string $recording_link = null;
    public string $status = '';
    public string $session_type = '';

    public array $courseSessionTemplates = [];
    public array $rooms = [];

    public function getSessionTemplateTitleProperty(): ?string
    {
        if ( ! $this->model->id || ! $this->model->sessionTemplate) {
            return null;
        }

        return "#{$this->model->sessionTemplate->order} - {$this->model->sessionTemplate->title}";
    }

    public function mount(CourseTemplate $courseTemplate, Course $course, CourseSession $courseSession): void
    {
        $this->courseTemplate = $courseTemplate;
        $this->course = $course;
        // Eager load sessionTemplate for edit mode
        $this->model = $courseSession->load('sessionTemplate');

        $this->rooms = Room::query()
            ->get()
            ->map(fn ($room) => ['value' => $room->id, 'label' => $room->name])
            ->toArray();

        // Load course session templates for the course from route
        $this->loadCourseSessionTemplates($this->course->id);

        // Populate form fields if editing
        if ($this->model->id) {
            $this->course_session_template_id = $this->model->course_session_template_id;
            $this->date = $this->model->date?->format('Y-m-d');
            $this->start_time = $this->model->start_time?->format('H:i');
            $this->end_time = $this->model->end_time?->format('H:i');
            $this->room_id = $this->model->room_id;
            $this->meeting_link = $this->model->meeting_link;
            $this->recording_link = $this->model->recording_link;
            $this->status = $this->model->status->value;
            $this->session_type = $this->model->session_type->value;
        } else {
            // Set defaults for new session
            $this->status = SessionStatus::PLANNED->value;
            $this->session_type = SessionType::IN_PERSON->value;
        }
    }

    protected function loadCourseSessionTemplates(int $courseId): void
    {
        $course = Course::with('template')->find($courseId);
        if ($course?->template) {
            $this->courseSessionTemplates = CourseSessionTemplate::where('course_template_id', $course->template->id)
                ->ordered()
                ->get()
                ->map(fn ($template) => [
                    'value' => $template->id,
                    'label' => "#{$template->order} - {$template->title}",
                ])
                ->toArray();
        } else {
            $this->courseSessionTemplates = [];
        }
    }

    protected function rules(): array
    {
        $rules = [
            'date' => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'room_id' => 'nullable|exists:rooms,id',
            'meeting_link' => 'nullable|string|url|max:255',
            'recording_link' => 'nullable|string|url|max:255',
            'status' => 'required|string|in:planned,done,cancelled',
            'session_type' => 'required|string|in:in-person,online,hybrid,self-paced',
        ];

        // In edit mode, course_session_template_id is not changeable, so no validation needed
        if ( ! $this->model->id) {
            $rules['course_session_template_id'] = 'required|exists:course_session_templates,id';
        }

        return $rules;
    }

    public function submit(): void
    {
        $payload = $this->validate();

        // Format the payload for the actions - always use course from route
        $formattedPayload = [
            'course_id' => $this->course->id,
            'course_session_template_id' => $this->model->id
                ? $this->model->course_session_template_id
                : $payload['course_session_template_id'],
            'date' => $payload['date'] ?? null,
            'start_time' => $payload['start_time'] ?? null,
            'end_time' => $payload['end_time'] ?? null,
            'room_id' => $payload['room_id'] ?? null,
            'meeting_link' => $payload['meeting_link'] ?? null,
            'recording_link' => $payload['recording_link'] ?? null,
            'status' => $payload['status'],
            'session_type' => $payload['session_type'],
        ];

        if ($this->model->id) {
            try {
                UpdateCourseSessionAction::run($this->model, $formattedPayload);
                $this->success(
                    title: trans('general.model_has_updated_successfully', ['model' => trans('courseSession.model')]),
                    redirectTo: route('admin.course-session.index', [
                        'courseTemplate' => $this->courseTemplate->id,
                        'course' => $this->course->id,
                    ])
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        } else {
            try {
                StoreCourseSessionAction::run($formattedPayload);
                $this->success(
                    title: trans('general.model_has_stored_successfully', ['model' => trans('courseSession.model')]),
                    redirectTo: route('admin.course-session.index', [
                        'courseTemplate' => $this->courseTemplate->id,
                        'course' => $this->course->id,
                    ])
                );
            } catch (Throwable $e) {
                $this->error($e->getMessage(), timeout: 5000);
            }
        }
    }

    public static function getSessionStatusOptions(): array
    {
        return [
            ['value' => SessionStatus::PLANNED->value, 'label' => SessionStatus::PLANNED->title()],
            ['value' => SessionStatus::DONE->value, 'label' => SessionStatus::DONE->title()],
            ['value' => SessionStatus::CANCELLED->value, 'label' => SessionStatus::CANCELLED->title()],
        ];
    }

    public function render(): View
    {
        $isEditMode = (bool) $this->model->id;
        $pageTitle = $isEditMode
            ? trans('general.page.edit.title', ['model' => trans('courseSession.model')])
            : trans('general.page.create.title', ['model' => trans('courseSession.model')]);

        $indexRoute = route('admin.course-session.index', [
            'courseTemplate' => $this->courseTemplate->id,
            'course' => $this->course->id,
        ]);

        return view('livewire.admin.pages.courseSession.courseSession-update-or-create', [
            'edit_mode' => $isEditMode,
            'sessionStatusOptions' => self::getSessionStatusOptions(),
            'sessionTypeOptions' => SessionType::options(),
            'breadcrumbs' => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link' => $indexRoute, 'label' => trans('general.page.index.title', ['model' => trans('courseSession.model')])],
                ['label' => $pageTitle],
            ],
            'breadcrumbsActions' => [
                ['link' => $indexRoute, 'icon' => 's-arrow-left'],
            ],
        ]);
    }
}
