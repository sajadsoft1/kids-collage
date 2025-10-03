<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Course;

use App\Actions\Course\StoreCourseAction;
use App\Enums\CourseStatusEnum;
use App\Enums\SessionStatus;
use App\Enums\SessionType;
use App\Models\CourseTemplate;
use App\Models\Room;
use App\Models\Term;
use App\Models\User;
use Exception;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mary\Traits\Toast;

class CourseRuner extends Component
{
    use Toast;

    public CourseTemplate $courseTemplate;
    public int $runningStep   = 1;
    public int $capacity      = 10;
    public int $price         = 100000;
    public string $status     = CourseStatusEnum::DRAFT->value;
    public int $term          = 0;
    public int $teacher       = 0;
    public int $room          = 0;
    public string $start_date = '';
    public string $end_date   = '';
    public string $start_time = '16:00';
    public string $end_time   = '18:00';
    public array $week_days   = [];
    public array $dayNames    = [
        '1' => 'شنبه',
        '2' => 'یکشنبه',
        '3' => 'دوشنبه',
        '4' => 'سه شنبه',
        '5' => 'چهارشنبه',
        '6' => 'پنجشنبه',
        '7' => 'جمعه',
    ];
    public array $sessions = [];

    public function mount(CourseTemplate $courseTemplate): void
    {
        $this->courseTemplate = $courseTemplate;
        $this->sessions       = $courseTemplate->sessionTemplates()->orderBy('order')->get()->map(fn ($session) => [
            'id'                 => $session->id,
            'course_template_id' => $session->course_template_id,
            'order'              => $session->order,
            'title'              => $session->title,
            'description'        => $session->description,
            'duration_minutes'   => $session->duration_minutes,
            'type'               => SessionType::ONLINE->value,
            'date'               => null,
            'start_time'         => $this->start_time,
            'end_time'           => $this->end_time,
            'room_id'            => 0,
            'link'               => 'https://meet.google.com',
        ])->toArray();
    }

    /** Handle individual week day toggle */
    public function toggleWeekDay($day): void
    {
        if ( ! is_array($this->week_days)) {
            $this->week_days = [];
        }

        if (in_array($day, $this->week_days)) {
            // Remove the day if it's already selected
            $this->week_days = array_values(array_diff($this->week_days, [$day]));
        } else {
            $this->week_days[] = $day;
        }
    }

    /** Get formatted week days for display */
    public function getFormattedWeekDaysProperty(): string
    {
        if (empty($this->week_days)) {
            return 'هیچ روزی انتخاب نشده';
        }

        // first sort the week days and then map the days to the day names
        $this->week_days = array_values(array_unique($this->week_days));
        sort($this->week_days);

        $selectedDays = array_map(fn ($day) => $this->dayNames[$day] ?? $day, $this->week_days);

        return implode('، ', $selectedDays);
    }

    /** Clear all selected week days */
    public function clearWeekDays(): void
    {
        $this->week_days = [];
    }

    /** Select all week days */
    public function selectAllWeekDays(): void
    {
        $this->week_days = ['1', '2', '3', '4', '5', '6', '7'];
    }

    public function updatedTerm($value): void
    {
        $term             = Term::find($value);
        $this->start_date = $term->start_date->format('Y-m-d');
        $this->end_date   = $term->end_date->format('Y-m-d');
    }

    public function updatedRoom($value): void
    {
        $this->room     = $value;
        $this->sessions = collect($this->sessions)->map(function ($session) use ($value) {
            $session['room_id'] = $value;

            return $session;
        })->toArray();
    }

    public function updated($property, $value): void
    {
        if ($property === 'start_date' && empty($value)) {
            $this->end_date = '';
        }

        if ( ! empty($this->week_days) && ! empty($this->start_date) && ! empty($this->end_date) && ! empty($this->start_time) && ! empty($this->end_time)) {
            $this->generateAndUpdateSessions($this->week_days, $this->start_date, $this->end_date, $this->start_time, $this->end_time);
        }
    }

    #[Computed]
    public function dates_example(): string|array
    {
        return $this->generateAndUpdateSessions($this->week_days, $this->start_date, $this->end_date, $this->start_time, $this->end_time);
    }

    /**
     * Generate sample dates with time based on week days and date range
     *
     * @param  array  $week_days  Array of selected week days (1-7)
     * @param  string $start_date Start date in Y-m-d format
     * @param  string $end_date   End date in Y-m-d format
     * @param  string $start_time Start time in H:i format
     * @param  string $end_time   End time in H:i format
     * @return array  Formatted array of generated dates
     */
    public function generateAndUpdateSessions($week_days, $start_date, $end_date, $start_time, $end_time): array|string
    {
        try {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $start_date);
            $endDate   = \Carbon\Carbon::createFromFormat('Y-m-d', $end_date);

            // Validate date range
            if ($startDate->gt($endDate)) {
                return 'تاریخ شروع نمی‌تواند بعد از تاریخ پایان باشد';
            }

            $generatedDates = [];
            $currentDate    = $startDate->copy();

            // Generate dates for each week day in the range
            while ($currentDate->lte($endDate)) {
                $dayOfWeek = $currentDate->dayOfWeekIso; // 1 = Monday, 7 = Sunday

                // Check if current day is in selected week days
                if (in_array((string) $dayOfWeek, $week_days)) {
                    $generatedDates[] = [
                        'date'       => $currentDate->format('Y-m-d'),
                        'day_name'   => $this->dayNames[(string) $dayOfWeek] ?? 'نامشخص',
                        'start_time' => $start_time,
                        'end_time'   => $end_time,
                        'formatted'  => $currentDate->format('Y/m/d') . ' (' . ($this->dayNames[(string) $dayOfWeek] ?? 'نامشخص') . ') - ' . $start_time . ' تا ' . $end_time,
                    ];
                }

                $currentDate->addDay();
            }

            if (empty($generatedDates)) {
                return 'در بازه زمانی انتخاب شده، هیچ روزی با روزهای هفته انتخاب شده مطابقت ندارد';
            }

            // Get the required number of sessions from course template
            $sessionCount = count($this->sessions);
            $dateCount    = count($generatedDates);
            if ($dateCount < $sessionCount) {
                return "تعداد جلسات ({$sessionCount}) بیشتر از تعداد تاریخ‌های موجود ({$dateCount}) است";
            }

            // Limit the generated dates to match the number of sessions
            $limitedDates = array_slice($generatedDates, 0, $sessionCount);
            // Update sessions with generated dates
            $this->sessions = collect($this->sessions)->map(function ($session, $index) use ($limitedDates) {
                $session['date']       = $limitedDates[$index]['date'];
                $session['start_time'] = $limitedDates[$index]['start_time'];
                $session['end_time']   = $limitedDates[$index]['end_time'];

                return $session;
            })->toArray();

            return $limitedDates;
        } catch (Exception $e) {
            return '';
        }
    }

    /** Navigate to the next step */
    public function nextStep(): void
    {
        if ($this->runningStep === 2) {
            $this->validateStep2();
        }
        if ($this->runningStep === 3) {
            $this->validateStep3();
        }
        if ($this->runningStep === 4) {
            dd($this->all());
        }
        if ($this->runningStep < 4) {
            $this->runningStep++;
        }
    }

    /** Navigate to the previous step */
    public function previousStep(): void
    {
        if ($this->runningStep > 1) {
            $this->runningStep--;
        }
    }

    public function validateStep2(): void
    {
        $this->validate([
            'term' => 'required|integer|min:1',
        ]);
        $term = Term::find($this->term);

        $this->validate([
            'capacity'    => 'required|integer|min:1',
            'price'       => 'required|integer|min:0',
            'status'      => 'required|in:' . collect(CourseStatusEnum::runerOptions())->pluck('value')->implode(','),
            'term'        => 'required|integer|min:1',
            'teacher'     => 'required|integer|min:1|exists:users,id',
            'room'        => 'required|integer|min:1|exists:rooms,id',
            'start_date'  => ['required', 'date_format:Y-m-d', 'after_or_equal:' . $term->start_date],
            'end_date'    => ['required', 'date_format:Y-m-d', 'before_or_equal:' . $term->end_date],
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'week_days'   => 'required|array',
            'week_days.*' => 'required|integer|min:1',
        ]);
    }

    public function validateStep3(): void
    {
        $this->validate([
            'sessions'                    => 'required|array',
            'sessions.*.title'            => 'required|string|max:255',
            'sessions.*.description'      => 'required|string',
            'sessions.*.duration_minutes' => 'required|integer|min:1',
            'sessions.*.type'             => 'required|in:' . collect(SessionType::options())->pluck('value')->implode(','),
            'sessions.*.room_id'          => 'required|integer|min:1|exists:rooms,id',
            'sessions.*.link'             => 'required|url',
            'sessions.*.date'             => 'required|date',
            'sessions.*.start_time'       => 'required|date_format:H:i',
            'sessions.*.end_time'         => 'required|date_format:H:i|after:start_time',
        ]);
    }

    /** Start the course */
    public function startCourse(): void
    {
        // TODO: Implement course start logic
        StoreCourseAction::run($this->courseTemplate, [
            'capacity'   => $this->capacity,
            'price'      => $this->price,
            'status'     => $this->status,
            'term_id'    => $this->term,
            'teacher_id' => $this->teacher,
            'sessions'   => collect($this->sessions)->map(fn ($session) => [
                'course_session_template_id' => $session['id'],
                'date'                       => $session['date'],
                'start_time'                 => $session['start_time'],
                'end_time'                   => $session['end_time'],
                'room_id'                    => $session['room_id'],
                'meeting_link'               => $session['link'],
                'session_type'               => $session['type'],
                'status'                     => SessionStatus::PLANNED->value,
            ])->toArray(),
        ]);

        $this->success(
            title: trans('general.model_has_stored_successfully', ['model' => trans('course.model')]),
            redirectTo: route('admin.course.index')
        );
    }

    public function render()
    {
        return view('livewire.admin.pages.course.course-runer', [
            'breadcrumbs'        => [
                ['link' => route('admin.dashboard'), 'icon' => 's-home'],
                ['link'  => route('admin.course-template.index'), 'label' => trans('general.page.index.title', ['model' => trans('courseTemplate.model')])],
                ['label' => $this->courseTemplate->title],
            ],
            'breadcrumbsActions' => [
                ['link' => route('admin.course-template.index'), 'icon' => 's-arrow-left'],
            ],
            'terms'              => Term::all()->map(fn ($term) => ['value' => $term->id, 'label' => $term->title]),
            'teachers'           => User::all()->map(fn ($teacher) => ['value' => $teacher->id, 'label' => $teacher->name]),
            'rooms'              => Room::all()->map(fn ($room) => ['value' => $room->id, 'label' => $room->name]),
            'informations'       => [
                [
                    'label' => trans('validation.attributes.category'),
                    'value' => $this->courseTemplate->category->title ?? 'N/A',
                ],
                [
                    'label' => trans('validation.attributes.tags'),
                    'value' => $this->courseTemplate->tags->map(fn ($tag) => $tag->name)->implode(', '),
                ],
                [
                    'label' => trans('validation.attributes.level'),
                    'value' => $this->courseTemplate->level?->title() ?? 'N/A',
                ],
                [
                    'label' => trans('validation.attributes.session_count'),
                    'value' => $this->courseTemplate->sessionTemplates()->count(),
                ],
            ],
        ]);
    }
}
