<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Apps;

use App\Enums\EnrollmentStatusEnum;
use App\Enums\UserTypeEnum;
use App\Models\CourseSession;
use App\Models\Task;
use App\Services\LivewireTemplates\CalendarTemplate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Mary\Traits\Toast;

class CalendarApp extends CalendarTemplate
{
    use Toast;

    public array $categoryFilters = [];
    public int $visibleEventsPerDay = 3;
    public bool $showTaskDrawer = false;
    public bool $showDayModal = false;
    public bool $showEventModal = false;
    public ?Carbon $selectedDay = null;
    public ?string $selectedEventId = null;
    public ?string $selectedEventType = null;

    #[Url(as: 'year')]
    public ?int $urlYear = null;

    #[Url(as: 'month')]
    public ?int $urlMonth = null;

    #[Url(as: 'view')]
    public ?string $urlViewMode = null;

    #[Url(as: 'filters')]
    public array $activeCategorySlugs = [];

    /** Track calendar changes to trigger re-render */
    public string $refreshKey = '';

    /** Selected month and year for dropdowns */
    public ?int $selectedYear = null;
    public ?int $selectedMonth = null;

    public array $taskForm = [
        'title' => '',
        'description' => '',
        'scheduled_for' => '',
    ];

    protected Collection $tasks;

    protected $rules = [
        'taskForm.title' => ['required', 'string', 'max:255'],
        'taskForm.description' => ['nullable', 'string', 'max:2000'],
        'taskForm.scheduled_for' => ['required', 'date'],
    ];

    protected $casts = [
        'selectedDay' => 'datetime',
    ];

    protected array $validationAttributes = [];

    public function afterMount($extras = []): void
    {
        // Set calendar type based on locale
        $locale = app()->getLocale();
        $this->calendarType = $locale === 'fa' ? 'jalali' : 'gregorian';

        // Initialize category filters (respects URL values if already set)
        $this->syncCategoryFilters();

        // Restore view mode from URL or default to 'month'
        if ($this->urlViewMode && in_array($this->urlViewMode, ['month', 'week', 'day', 'list'])) {
            $this->viewMode = $this->urlViewMode;
        } else {
            $this->viewMode = 'month';
            $this->urlViewMode = 'month';
        }

        // Sync URL parameters with calendar date, or use today
        if ($this->urlYear && $this->urlMonth) {
            $this->syncRangeWithCalendarType($this->urlYear, $this->urlMonth);
        } else {
            // Set URL to current date
            if ($this->calendarType === 'jalali') {
                $jalali = \Morilog\Jalali\Jalalian::fromCarbon($this->startsAt);
                $this->urlYear = $jalali->getYear();
                $this->urlMonth = $jalali->getMonth();
            } else {
                $this->urlYear = $this->startsAt->year;
                $this->urlMonth = $this->startsAt->month;
            }
        }

        // Initialize selected month and year from current calendar date
        $this->updateSelectedMonthYear();

        $this->tasks = collect();
        $this->taskForm['scheduled_for'] = now()->format('Y-m-d H:i:s');
        $this->validationAttributes = [
            'taskForm.title' => __('calendar.form.title'),
            'taskForm.description' => __('calendar.form.description'),
            'taskForm.scheduled_for' => __('calendar.form.scheduled_for'),
        ];
    }

    public function updatedStartsAt(): void
    {
        // Update URL when calendar date changes
        if ($this->calendarType === 'jalali') {
            $jalali = \Morilog\Jalali\Jalalian::fromCarbon($this->startsAt);
            $this->urlYear = $jalali->getYear();
            $this->urlMonth = $jalali->getMonth();
        } else {
            $this->urlYear = $this->startsAt->year;
            $this->urlMonth = $this->startsAt->month;
        }

        // Update selected month and year
        $this->updateSelectedMonthYear();
    }

    public function updatedViewMode(): void
    {
        // Sync viewMode with URL
        $this->urlViewMode = $this->viewMode;
    }

    public function setViewMode(string $mode): void
    {
        parent::setViewMode($mode);

        // Sync with URL
        $this->urlViewMode = $this->viewMode;
        $this->refreshEvents();
    }

    /** Generate a unique key based on URL parameters for wire:key */
    public function getUrlKey(): string
    {
        $parts = [
            $this->urlYear ?? 'y',
            $this->urlMonth ?? 'm',
            $this->urlViewMode ?? 'v',
            implode('-', $this->activeCategorySlugs) ?: 'all',
            $this->refreshKey ?: 'init',
        ];

        return implode('-', $parts);
    }

    public function updatedSelectedYear(): void
    {
        if ($this->selectedYear && $this->selectedMonth) {
            $this->syncRangeWithCalendarType($this->selectedYear, $this->selectedMonth);
            $this->updatedStartsAt();
            $this->refreshEvents();
        }
    }

    public function updatedSelectedMonth(): void
    {
        if ($this->selectedYear && $this->selectedMonth) {
            $this->syncRangeWithCalendarType($this->selectedYear, $this->selectedMonth);
            $this->updatedStartsAt();
            $this->refreshEvents();
        }
    }

    protected function updateSelectedMonthYear(): void
    {
        if ($this->calendarType === 'jalali') {
            $jalali = \Morilog\Jalali\Jalalian::fromCarbon($this->startsAt);
            $this->selectedYear = $jalali->getYear();
            $this->selectedMonth = $jalali->getMonth();
        } else {
            $this->selectedYear = $this->startsAt->year;
            $this->selectedMonth = $this->startsAt->month;
        }
    }

    public function getAvailableMonths(): array
    {
        $months = [];

        if ($this->calendarType === 'jalali') {
            $monthNames = [
                1 => 'فروردین',
                2 => 'اردیبهشت',
                3 => 'خرداد',
                4 => 'تیر',
                5 => 'مرداد',
                6 => 'شهریور',
                7 => 'مهر',
                8 => 'آبان',
                9 => 'آذر',
                10 => 'دی',
                11 => 'بهمن',
                12 => 'اسفند',
            ];
        } else {
            $monthNames = [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December',
            ];
        }

        foreach ($monthNames as $key => $value) {
            $months[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        return $months;
    }

    public function getAvailableYears(): array
    {
        $currentYear = $this->calendarType === 'jalali'
            ? \Morilog\Jalali\Jalalian::fromCarbon(now())->getYear()
            : now()->year;

        $years = [];
        // Generate years from 5 years ago to 5 years ahead
        for ($i = -5; $i <= 5; $i++) {
            $year = $currentYear + $i;
            $years[] = [
                'value' => $year,
                'label' => (string) $year,
            ];
        }

        return $years;
    }

    public function events(): Collection
    {
        $this->refreshTasks();

        if (empty($this->activeCategorySlugs)) {
            return collect();
        }

        return collect()
            ->when($this->isFilterActive('tasks'), fn (Collection $events) => $events->merge($this->taskEvents()))
            ->when($this->isFilterActive('classes'), fn (Collection $events) => $events->merge($this->classEvents()))
            ->sortBy('date')
            ->values();
    }

    public function toggleCategoryFilter(string $slug): void
    {
        if ( ! collect($this->categoryFilters)->pluck('slug')->contains($slug)) {
            return;
        }

        if ($this->isFilterActive($slug)) {
            $this->activeCategorySlugs = array_values(array_diff($this->activeCategorySlugs, [$slug]));
        } else {
            $this->activeCategorySlugs[] = $slug;
        }

        // Force refresh events when filter changes
        $this->refreshEvents();
    }

    public function resetCategoryFilters(): void
    {
        $this->activeCategorySlugs = collect($this->categoryFilters)->pluck('slug')->all();
        // Force refresh events when filter resets
        $this->refreshEvents();
    }

    public function updatedActiveCategorySlugs(): void
    {
        // Refresh events when activeCategorySlugs array changes
        $this->refreshEvents();
    }

    public function saveTask(): void
    {
        abort_if( ! Auth::check(), 403);

        $validated = $this->validate();

        if ($this->isEditingTask()) {
            $this->updateTask($validated);
        } else {
            $this->createTask($validated);
        }
    }

    protected function createTask(array $validated): void
    {
        Task::create([
            'user_id' => Auth::id(),
            'title' => $validated['taskForm']['title'],
            'description' => $validated['taskForm']['description'],
            'scheduled_for' => Carbon::parse($validated['taskForm']['scheduled_for'], config('app.timezone')),
            'status' => Task::STATUS_PENDING,
        ]);

        $this->resetTaskForm();
        $this->refreshTasks();
        $this->showTaskDrawer = false;

        $this->success(__('calendar.messages.task_created'));
    }

    protected function updateTask(array $validated): void
    {
        if ( ! $this->selectedEventId) {
            $this->error(__('calendar.messages.task_not_found'));

            return;
        }

        $task = Task::find($this->selectedEventId);

        if ( ! $task || $task->user_id !== Auth::id()) {
            $this->error(__('calendar.messages.unauthorized'));

            return;
        }

        $task->update([
            'title' => $validated['taskForm']['title'],
            'description' => $validated['taskForm']['description'],
            'scheduled_for' => Carbon::parse($validated['taskForm']['scheduled_for'], config('app.timezone')),
        ]);

        $this->resetTaskForm();
        $this->refreshTasks();
        $this->showTaskDrawer = false;
        $this->selectedEventId = null;
        $this->selectedEventType = null;

        $this->success(__('calendar.messages.task_updated'));
    }

    public function onDayClick($year, $month, $day): void
    {
        $this->selectedDay = Carbon::createFromDate($year, $month, $day)->startOfDay();
        $this->taskForm['schedule_for'] = $this->selectedDay->format('Y-m-d H:i:s');
        $this->showDayModal = true;
    }

    public function getSelectedDayEvents(): Collection
    {
        if ( ! $this->selectedDay) {
            return collect();
        }

        $allEvents = $this->events();

        return $allEvents->filter(function ($event) {
            $eventDate = $event['date'] instanceof Carbon ? $event['date'] : Carbon::parse($event['date']);

            return $eventDate->isSameDay($this->selectedDay);
        })->values();
    }

    public function onEventClick($eventId): void
    {
        $this->selectedEventId = (string) $eventId;
        $event = $this->findEventById($eventId);

        if ( ! $event) {
            $this->error(__('calendar.messages.event_not_found'));

            return;
        }

        $eventType = $event['type'] ?? null;
        $this->selectedEventType = $eventType;

        // Handle task events
        if ($eventType === 'task') {
            // Extract actual task ID from prefixed ID (task-123 -> 123)
            $taskId = str_starts_with($eventId, 'task-') ? substr($eventId, 5) : $eventId;
            $task = Task::find($taskId);

            if ($task && $task->user_id === Auth::id()) {
                $this->taskForm = [
                    'title' => $task->title,
                    'description' => $task->description ?? '',
                    'scheduled_for' => $task->scheduled_for->format('Y-m-d H:i:s'),
                ];
                $this->showDayModal = false;
                $this->showEventModal = false;
                $this->showTaskDrawer = true;
            } else {
                // Show event modal for tasks user doesn't own
                $this->showDayModal = false;
                $this->showEventModal = true;
            }
        } else {
            // Handle class events or other event types
            $this->showDayModal = false;
            $this->showEventModal = true;
        }
    }

    public function onEventDropped($eventId, $year, $month, $day): void
    {
        $event = $this->findEventById($eventId);

        if ( ! $event) {
            $this->error(__('calendar.messages.event_not_found'));

            return;
        }

        $eventType = $event['type'] ?? null;

        // Prevent moving classes - only tasks can be moved
        if ($eventType === 'class' || $eventType !== 'task') {
            if ($eventType === 'class') {
                $this->info(__('calendar.messages.class_move_not_supported'));
            }

            return;
        }

        // Only handle task events - extract actual task ID from prefixed ID
        if ($eventType === 'task') {
            $taskId = str_starts_with($eventId, 'task-') ? substr($eventId, 5) : $eventId;
            $task = Task::find($taskId);

            if ( ! $task) {
                $this->error(__('calendar.messages.task_not_found'));

                return;
            }

            if ($task->user_id !== Auth::id()) {
                $this->error(__('calendar.messages.unauthorized'));

                return;
            }

            $newDate = Carbon::createFromDate($year, $month, $day)->startOfDay();
            $oldTime = $task->scheduled_for->format('H:i:s');
            $newDateTime = $newDate->clone()->setTimeFromTimeString($oldTime);

            $task->update([
                'scheduled_for' => $newDateTime,
            ]);

            $this->refreshTasks();
            $this->refreshEvents();
            $this->success(__('calendar.messages.task_moved'));
        }
    }

    protected function findEventById($eventId): ?array
    {
        // First try to find in current events
        $allEvents = $this->events();
        $event = $allEvents->firstWhere('id', (string) $eventId);

        if ($event) {
            return $event;
        }

        // Parse prefixed ID to determine type and actual ID
        $isTask = str_starts_with($eventId, 'task-');
        $isClass = str_starts_with($eventId, 'class-');
        $actualId = $isTask ? substr($eventId, 5) : ($isClass ? substr($eventId, 6) : $eventId);

        // If not found, check if it's a task (tasks might be filtered out)
        if ($isTask || ! $isClass) {
            $task = Task::find($actualId);
            if ($task && $task->user_id === Auth::id()) {
                $category = collect($this->categoryFilters)->firstWhere('slug', 'tasks');
                if ($category) {
                    return [
                        'id' => 'task-' . $task->getKey(),
                        'type' => 'task',
                        'title' => $task->title,
                        'description' => $task->description,
                        'date' => $task->scheduled_for,
                        'end' => $task->scheduled_for->clone()->addHour(),
                        'category' => $category,
                        'location' => null,
                    ];
                }
            }
        }

        // Check if it's a class session
        if ($isClass || ! $isTask) {
            $session = CourseSession::find($actualId);
        } else {
            $session = null;
        }
        if ($session) {
            $category = collect($this->categoryFilters)->firstWhere('slug', 'classes');
            if ($category) {
                $eventDate = $session->date ? $session->date->clone() : now();
                if ($session->start_time) {
                    if ($session->start_time instanceof Carbon) {
                        $eventDate->setTime(
                            (int) $session->start_time->format('H'),
                            (int) $session->start_time->format('i'),
                            (int) $session->start_time->format('s')
                        );
                    } else {
                        $timeParts = explode(':', (string) $session->start_time);
                        $eventDate->setTime(
                            (int) ($timeParts[0] ?? 0),
                            (int) ($timeParts[1] ?? 0),
                            (int) ($timeParts[2] ?? 0)
                        );
                    }
                }

                $endDate = $eventDate->clone();
                if ($session->end_time) {
                    if ($session->end_time instanceof Carbon) {
                        $endDate->setTime(
                            (int) $session->end_time->format('H'),
                            (int) $session->end_time->format('i'),
                            (int) $session->end_time->format('s')
                        );
                    } else {
                        $endTimeParts = explode(':', (string) $session->end_time);
                        $endDate->setTime(
                            (int) ($endTimeParts[0] ?? 0),
                            (int) ($endTimeParts[1] ?? 0),
                            (int) ($endTimeParts[2] ?? 0)
                        );
                    }
                } else {
                    $endDate->addHour();
                }

                return [
                    'id' => 'class-' . $session->getKey(),
                    'type' => 'class',
                    'title' => $session->sessionTemplate->title ?? $session->course->template->title ?? __('calendar.filters.classes'),
                    'description' => $session->sessionTemplate->description ?? null,
                    'date' => $eventDate,
                    'end' => $endDate,
                    'category' => $category,
                    'location' => $session->location ?? null,
                ];
            }
        }

        return null;
    }

    protected function viewData(): array
    {
        return [
            'categoryFilters' => $this->categoryFilters,
            'activeCategorySlugs' => $this->activeCategorySlugs,
            'visibleEventsPerDay' => $this->visibleEventsPerDay,
            'selectedDay' => $this->selectedDay,
            'selectedDayEvents' => $this->getSelectedDayEvents(),
            'selectedEvent' => $this->getSelectedEvent(),
            'isEditingTask' => $this->isEditingTask(),
            'selectedYear' => $this->selectedYear,
            'selectedMonth' => $this->selectedMonth,
            'availableMonths' => $this->getAvailableMonths(),
            'availableYears' => $this->getAvailableYears(),
            'urlKey' => $this->getUrlKey(),
        ];
    }

    protected function getSelectedEvent(): ?array
    {
        if ( ! $this->selectedEventId) {
            return null;
        }

        return $this->findEventById($this->selectedEventId);
    }

    protected function syncCategoryFilters(): void
    {
        $this->categoryFilters = array_values($this->categoryPalette());
        // Don't reset activeCategorySlugs if already set from URL
        if (empty($this->activeCategorySlugs)) {
            $this->activeCategorySlugs = collect($this->categoryFilters)->pluck('slug')->all();
        }
    }

    protected function categoryPalette(): array
    {
        return [
            'tasks' => [
                'slug' => 'tasks',
                'label' => __('calendar.filters.tasks'),
                'chip_classes' => 'bg-indigo-50 text-indigo-700 border border-indigo-200',
                'event_classes' => 'bg-indigo-50 border border-indigo-200 text-indigo-900',
                'dot_classes' => 'bg-indigo-500',
            ],
            'classes' => [
                'slug' => 'classes',
                'label' => __('calendar.filters.classes'),
                'chip_classes' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                'event_classes' => 'bg-emerald-50 border border-emerald-200 text-emerald-900',
                'dot_classes' => 'bg-emerald-500',
            ],
        ];
    }

    protected function refreshTasks(): void
    {
        $userId = Auth::id();

        $this->tasks = Task::query()
            ->when($userId, fn ($query) => $query->where('user_id', $userId))
            ->when($this->gridStartsAt && $this->gridEndsAt, fn ($query) => $query->whereBetween('scheduled_for', [
                $this->gridStartsAt->clone()->startOfDay(),
                $this->gridEndsAt->clone()->endOfDay(),
            ]))
            ->orderBy('scheduled_for')
            ->get();
    }

    protected function taskEvents(): Collection
    {
        $category = collect($this->categoryFilters)->firstWhere('slug', 'tasks');

        // If category not found, return empty collection
        if ( ! $category) {
            return collect();
        }

        return $this->tasks->map(function (Task $task) use ($category) {
            return [
                'id' => 'task-' . $task->getKey(),
                'type' => 'task',
                'title' => $task->title,
                'description' => $task->description,
                'date' => $task->scheduled_for,
                'end' => $task->scheduled_for->clone()->addHour(),
                'category' => $category,
                'location' => null,
            ];
        });
    }

    protected function classEvents(): Collection
    {
        $sessions = $this->getUserClassSessions();
        $category = collect($this->categoryFilters)->firstWhere('slug', 'classes');

        if ( ! $category) {
            return collect();
        }

        return $sessions->map(function (CourseSession $session) use ($category) {
            // Combine date and start_time for the event date
            $eventDate = $session->date ? $session->date->clone() : now();

            if ($session->start_time) {
                // Handle time - it might be a Carbon instance or a string
                if ($session->start_time instanceof Carbon) {
                    $eventDate->setTime(
                        (int) $session->start_time->format('H'),
                        (int) $session->start_time->format('i'),
                        (int) $session->start_time->format('s')
                    );
                } else {
                    // If it's a string, parse it
                    $timeParts = explode(':', (string) $session->start_time);
                    $eventDate->setTime(
                        (int) ($timeParts[0] ?? 0),
                        (int) ($timeParts[1] ?? 0),
                        (int) ($timeParts[2] ?? 0)
                    );
                }
            }

            // Calculate end date/time
            $endDate = $eventDate->clone();
            if ($session->end_time) {
                if ($session->end_time instanceof Carbon) {
                    $endDate->setTime(
                        (int) $session->end_time->format('H'),
                        (int) $session->end_time->format('i'),
                        (int) $session->end_time->format('s')
                    );
                } else {
                    $endTimeParts = explode(':', (string) $session->end_time);
                    $endDate->setTime(
                        (int) ($endTimeParts[0] ?? 0),
                        (int) ($endTimeParts[1] ?? 0),
                        (int) ($endTimeParts[2] ?? 0)
                    );
                }
            } else {
                // Default to 1 hour if no end time
                $endDate->addHour();
            }

            return [
                'id' => 'class-' . $session->getKey(),
                'type' => 'class',
                'title' => $session->sessionTemplate->title ?? $session->course->template->title ?? __('calendar.filters.classes'),
                'description' => $session->sessionTemplate->description ?? null,
                'date' => $eventDate,
                'end' => $endDate,
                'category' => $category,
                'location' => $session->location ?? null,
            ];
        });
    }

    protected function getUserClassSessions(): Collection
    {
        $user = Auth::user();

        if ( ! $user) {
            return collect();
        }

        $query = CourseSession::query()
            ->with(['course.template', 'sessionTemplate', 'room'])
            ->whereNotNull('date')
            ->when($this->gridStartsAt && $this->gridEndsAt, fn ($q) => $q->whereBetween('date', [
                $this->gridStartsAt->clone()->startOfDay(),
                $this->gridEndsAt->clone()->endOfDay(),
            ]));

        return match ($user->type) {
            UserTypeEnum::USER => $this->getStudentClassSessions($query, $user),
            UserTypeEnum::TEACHER => $this->getTeacherClassSessions($query, $user),
            UserTypeEnum::PARENT => $this->getParentClassSessions($query, $user),
            default => collect(),
        };
    }

    protected function getStudentClassSessions($query, $user): Collection
    {
        return $query
            ->whereHas('course.enrollments', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->where('status', EnrollmentStatusEnum::ACTIVE->value);
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    }

    protected function getTeacherClassSessions($query, $user): Collection
    {
        return $query
            ->whereHas('course', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    }

    protected function getParentClassSessions($query, $user): Collection
    {
        $childrenIds = $user->children()->pluck('id');

        if ($childrenIds->isEmpty()) {
            return collect();
        }

        return $query
            ->whereHas('course.enrollments', function ($q) use ($childrenIds) {
                $q->whereIn('user_id', $childrenIds)
                    ->where('status', EnrollmentStatusEnum::ACTIVE->value);
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    }

    protected function isFilterActive(string $slug): bool
    {
        return in_array($slug, $this->activeCategorySlugs, true);
    }

    protected function resetTaskForm(): void
    {
        $this->taskForm = [
            'title' => '',
            'description' => '',
            'scheduled_for' => now()->format('Y-m-d H:i:s'),
        ];
        $this->selectedEventId = null;
        $this->selectedEventType = null;
    }

    public function isEditingTask(): bool
    {
        return $this->selectedEventId !== null && $this->selectedEventType === 'task';
    }

    protected function shiftCalendarMonth(int $months): void
    {
        if ($months === 0) {
            return;
        }

        if ($this->calendarType === 'jalali') {
            $current = \Morilog\Jalali\Jalalian::fromCarbon($this->startsAt);
            $target = $months > 0 ? $current->addMonths($months) : $current->subMonths(abs($months));
            $this->setRangeFromJalali($target->getYear(), $target->getMonth());
            $this->updatedStartsAt();
            $this->refreshEvents();

            return;
        }

        $this->startsAt = ($months > 0
            ? $this->startsAt->clone()->addMonths($months)
            : $this->startsAt->clone()->subMonths(abs($months)))
            ->startOfMonth()
            ->startOfDay();

        $this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();

        $this->calculateGridStartsEnds();
        $this->updatedStartsAt();
        $this->refreshEvents();
    }

    protected function refreshEvents(): void
    {
        // Force refresh by updating refreshKey which triggers re-render
        $this->refreshKey = uniqid();
    }

    protected function setRangeFromGregorian(int $year, int $month): void
    {
        parent::setRangeFromGregorian($year, $month);
        $this->updatedStartsAt();
        $this->refreshEvents();
    }

    protected function setRangeFromJalali(int $year, int $month): void
    {
        parent::setRangeFromJalali($year, $month);
        $this->updatedStartsAt();
        $this->refreshEvents();
    }

    public function goToCurrentMonth(): void
    {
        $this->syncRangeWithCalendarType();
        $this->currentCursorDate = $this->startsAt->clone();
        $this->updatedStartsAt();
        $this->refreshEvents();
    }

    public function setCalendarType(string $calendarType): void
    {
        parent::setCalendarType($calendarType);
        $this->updateSelectedMonthYear();
        $this->updatedStartsAt();
        $this->refreshEvents();
    }
}
