<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Apps;

use App\Models\Task;
use App\Services\LivewireTemplates\CalendarTemplate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Mary\Traits\Toast;

class CalendarApp extends CalendarTemplate
{
    use Toast;
    public array $categoryFilters = [];
    public array $activeCategorySlugs = [];
    public int $visibleEventsPerDay = 3;
    public bool $showTaskDrawer = false;
    public bool $showDayModal = false;
    public bool $showEventModal = false;
    public ?Carbon $selectedDay = null;
    public ?string $selectedEventId = null;
    public ?string $selectedEventType = null;
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
        $this->tasks = collect();
        $this->taskForm['scheduled_for'] = now()->format('Y-m-d\TH:i');
        $this->syncCategoryFilters();
        $this->validationAttributes = [
            'taskForm.title' => __('calendar.form.title'),
            'taskForm.description' => __('calendar.form.description'),
            'taskForm.scheduled_for' => __('calendar.form.scheduled_for'),
        ];
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

            return;
        }

        $this->activeCategorySlugs[] = $slug;
    }

    public function resetCategoryFilters(): void
    {
        $this->activeCategorySlugs = collect($this->categoryFilters)->pluck('slug')->all();
    }

    public function createTask(): void
    {
        abort_if( ! Auth::check(), 403);

        $validated = $this->validate();

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

    public function updateTask(): void
    {
        abort_if( ! Auth::check(), 403);

        if ( ! $this->selectedEventId) {
            $this->error(__('calendar.messages.task_not_found'));

            return;
        }

        $task = Task::find($this->selectedEventId);

        if ( ! $task || $task->user_id !== Auth::id()) {
            $this->error(__('calendar.messages.unauthorized'));

            return;
        }

        $validated = $this->validate();

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

        $this->selectedEventType = $event['type'] ?? null;

        if ($this->selectedEventType === 'task') {
            $task = Task::find($eventId);
            if ($task && $task->user_id === Auth::id()) {
                $this->taskForm = [
                    'title' => $task->title,
                    'description' => $task->description ?? '',
                    'scheduled_for' => $task->scheduled_for->format('Y-m-d\TH:i'),
                ];
                $this->showDayModal = false;
                $this->showEventModal = false;
                $this->showTaskDrawer = true;
            } else {
                $this->showDayModal = false;
                $this->showEventModal = true;
            }
        } else {
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

        $newDate = Carbon::createFromDate($year, $month, $day)->startOfDay();
        $eventType = $event['type'] ?? null;

        if ($eventType === 'task') {
            $task = Task::find($eventId);

            if ( ! $task) {
                $this->error(__('calendar.messages.task_not_found'));

                return;
            }

            if ($task->user_id !== Auth::id()) {
                $this->error(__('calendar.messages.unauthorized'));

                return;
            }

            $oldTime = $task->scheduled_for->format('H:i:s');
            $newDateTime = $newDate->clone()->setTimeFromTimeString($oldTime);

            $task->update([
                'scheduled_for' => $newDateTime,
            ]);

            $this->refreshTasks();
            // $this->dispatch('$refresh');
            $this->render();
            $this->success(__('calendar.messages.task_moved'));
        } else {
            $this->info(__('calendar.messages.class_move_not_supported'));
            // $this->dispatch('$refresh');
            $this->render();
        }
    }

    protected function findEventById($eventId): ?array
    {
        $allEvents = $this->events();

        return $allEvents->firstWhere('id', (string) $eventId);
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
        $this->activeCategorySlugs = collect($this->categoryFilters)->pluck('slug')->all();
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

        return $this->tasks->map(function (Task $task) use ($category) {
            return [
                'id' => (string) $task->getKey(),
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
        return collect();
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
            'scheduled_for' => now()->format('Y-m-d\TH:i'),
        ];
        $this->selectedEventId = null;
        $this->selectedEventType = null;
    }

    public function isEditingTask(): bool
    {
        return $this->selectedEventId !== null && $this->selectedEventType === 'task';
    }
}
