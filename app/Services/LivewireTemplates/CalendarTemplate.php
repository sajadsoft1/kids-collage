<?php

declare(strict_types=1);

namespace App\Services\LivewireTemplates;

use Carbon\Carbon;
use DateTimeZone;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class CalendarTemplate extends Component
{
    public $startsAt;
    public $endsAt;

    public $gridStartsAt;
    public $gridEndsAt;

    public $weekStartsAt;
    public $weekEndsAt;

    public $calendarView;
    public $dayView;
    public $eventView;
    public $dayOfWeekView;

    public $calendarType = 'jalali';
    public $availableCalendarTypes = [];

    public $viewMode = 'month';
    public $viewModeOptions = [];

    public $currentCursorDate;

    public $dragAndDropClasses;

    public $beforeCalendarView;
    public $afterCalendarView;

    public $pollMillis;
    public $pollAction;

    public $dragAndDropEnabled;
    public $dayClickEnabled;
    public $eventClickEnabled;

    protected $casts = [
        'startsAt' => 'date',
        'endsAt' => 'date',
        'gridStartsAt' => 'date',
        'gridEndsAt' => 'date',
        'currentCursorDate' => 'date',
    ];

    protected ?int $customWeekStartsAt = null;

    public function mount(
        $initialYear = null,
        $initialMonth = null,
        $weekStartsAt = null,
        $calendarView = null,
        $dayView = null,
        $eventView = null,
        $dayOfWeekView = null,
        $dragAndDropClasses = null,
        $beforeCalendarView = null,
        $afterCalendarView = null,
        $pollMillis = null,
        $pollAction = null,
        $dragAndDropEnabled = true,
        $dayClickEnabled = true,
        $eventClickEnabled = true,
        $extras = []
    ) {
        $this->customWeekStartsAt = $weekStartsAt;
        $this->calendarType = data_get($extras, 'calendarType', $this->calendarType);
        $this->availableCalendarTypes = data_get($extras, 'calendarTypes', $this->availableCalendarTypes);
        $this->applyWeekBoundaries();

        $this->syncRangeWithCalendarType($initialYear, $initialMonth);

        $this->setupViews($calendarView, $dayView, $eventView, $dayOfWeekView, $beforeCalendarView, $afterCalendarView);

        $this->setupPoll($pollMillis, $pollAction);

        $this->dragAndDropEnabled = $dragAndDropEnabled;
        $this->dragAndDropClasses = $dragAndDropClasses ?? 'border border-blue-400 border-4';

        $this->dayClickEnabled = $dayClickEnabled;
        $this->eventClickEnabled = $eventClickEnabled;

        $this->currentCursorDate ??= $this->startsAt->clone();

        $this->availableCalendarTypes = [
            'gregorian' => __('calendar.calendar_type.gregorian'),
            'jalali' => __('calendar.calendar_type.jalali'),
        ];
        $this->viewModeOptions = [
            'month' => __('calendar.view_mode.month'),
            'week' => __('calendar.view_mode.week'),
            'day' => __('calendar.view_mode.day'),
            'list' => __('calendar.view_mode.list'),
        ];
        $this->afterMount($extras);
    }

    public function afterMount($extras = []) {}

    public function setupViews(
        $calendarView = null,
        $dayView = null,
        $eventView = null,
        $dayOfWeekView = null,
        $beforeCalendarView = null,
        $afterCalendarView = null
    ) {
        $this->calendarView = $calendarView ?? 'templates.calendar.calendar-app';
        $this->dayView = $dayView ?? 'templates.calendar.day';
        $this->eventView = $eventView ?? 'templates.calendar.event';
        $this->dayOfWeekView = $dayOfWeekView ?? 'templates.calendar.day-of-week';

        $this->beforeCalendarView = $beforeCalendarView ?? null;
        $this->afterCalendarView = $afterCalendarView ?? null;
    }

    public function setupPoll($pollMillis, $pollAction)
    {
        $this->pollMillis = $pollMillis;
        $this->pollAction = $pollAction;
    }

    public function goToPreviousMonth()
    {
        if ($this->viewMode === 'month' || $this->viewMode === 'list') {
            $this->shiftCalendarMonth(-1);

            return;
        }

        if ($this->viewMode === 'week') {
            $this->shiftCursorWeeks(-1);

            return;
        }

        $this->shiftCursorDays(-1);
    }

    public function goToNextMonth()
    {
        if ($this->viewMode === 'month' || $this->viewMode === 'list') {
            $this->shiftCalendarMonth(1);

            return;
        }

        if ($this->viewMode === 'week') {
            $this->shiftCursorWeeks(1);

            return;
        }

        $this->shiftCursorDays(1);
    }

    public function goToCurrentMonth()
    {
        $this->syncRangeWithCalendarType();
        $this->currentCursorDate = $this->startsAt->clone();
    }

    public function calculateGridStartsEnds()
    {
        $this->gridStartsAt = $this->startsAt->clone()->startOfWeek($this->weekStartsAt);
        $this->gridEndsAt = $this->endsAt->clone()->endOfWeek($this->weekEndsAt);
    }

    /** @throws Exception */
    public function monthGrid()
    {
        $firstDayOfGrid = $this->gridStartsAt;
        $lastDayOfGrid = $this->gridEndsAt;

        $numbersOfWeeks = ceil($lastDayOfGrid->diffInWeeks($firstDayOfGrid, true));
        $days = ceil($lastDayOfGrid->diffInDays($firstDayOfGrid, true));

        if ($days % 7 != 0) {
            throw new Exception('Livewire Calendar not correctly configured. Check initial inputs.');
        }

        $monthGrid = collect();
        $currentDay = $firstDayOfGrid->clone();

        while ( ! $currentDay->greaterThan($lastDayOfGrid)) {
            $monthGrid->push($currentDay->clone());
            $currentDay->addDay();
        }

        $monthGrid = $monthGrid->chunk(7);
        if ($numbersOfWeeks != $monthGrid->count()) {
            throw new Exception('Livewire Calendar calculated wrong number of weeks. Sorry :(');
        }

        return $monthGrid;
    }

    public function events(): Collection
    {
        return collect();
    }

    public function getEventsForDay($day, Collection $events): Collection
    {
        return $events
            ->filter(function ($event) use ($day) {
                return Carbon::parse($event['date'])->isSameDay($day);
            });
    }

    public function onDayClick($year, $month, $day) {}

    public function onEventClick($eventId) {}

    public function onEventDropped($eventId, $year, $month, $day) {}

    /**
     * @return Factory|View
     * @throws Exception
     */
    public function render()
    {
        $events = $this->events();
        $currentCursor = $this->currentCursor();

        return view($this->calendarView)
            ->with(array_merge([
                'componentId' => $this->getId(),
                'monthGrid' => $this->monthGrid(),
                'events' => $events,
                'calendarType' => $this->calendarType,
                'availableCalendarTypes' => $this->availableCalendarTypes,
                'viewMode' => $this->viewMode,
                'viewModeOptions' => $this->viewModeOptions,
                'currentCursor' => $currentCursor,
                'weekDays' => $this->weekDays(),
                'dayEvents' => $this->getEventsForDay($currentCursor, $events),
                'listEventGroups' => $this->groupEventsByDate($events),
                'currentMonthLabel' => $this->currentMonthLabel(),
                'currentMonthSubtitle' => $this->currentMonthSubtitle(),
                'getEventsForDay' => function ($day) use ($events) {
                    return $this->getEventsForDay($day, $events);
                },
            ], $this->viewData()))->layout(config('livewire.layout'), [
                'fullWidth' => true,
            ]);
    }

    public function setCalendarType(string $calendarType): void
    {
        if ( ! array_key_exists($calendarType, $this->availableCalendarTypes)) {
            return;
        }

        if ($this->calendarType === $calendarType) {
            return;
        }

        $this->calendarType = $calendarType;
        if ($calendarType === 'jalali') {
            $jalali = Jalalian::fromCarbon($this->startsAt);
            $this->setRangeFromJalali($jalali->getYear(), $jalali->getMonth());

            return;
        }

        $this->setRangeFromGregorian($this->startsAt->year, $this->startsAt->month);
    }

    public function setViewMode(string $mode): void
    {
        if ( ! array_key_exists($mode, $this->viewModeOptions)) {
            return;
        }

        if ($this->viewMode === $mode) {
            return;
        }

        $this->viewMode = $mode;

        if ($mode === 'month') {
            $this->currentCursorDate = $this->startsAt->clone();

            return;
        }

        if ($this->currentCursorDate === null) {
            $this->currentCursorDate = $this->startsAt->clone();
        }
    }

    protected function viewData(): array
    {
        return [];
    }

    protected function currentCursor(): Carbon
    {
        return ($this->currentCursorDate ?? $this->startsAt)->clone();
    }

    protected function shiftCursorDays(int $days): void
    {
        $this->currentCursorDate = $this->currentCursor()->addDays($days)->startOfDay();
    }

    protected function shiftCursorWeeks(int $weeks): void
    {
        $this->currentCursorDate = $this->currentCursor()->addWeeks($weeks)->startOfDay();
    }

    protected function syncRangeWithCalendarType(?int $year = null, ?int $month = null): void
    {
        if ($this->calendarType === 'jalali') {
            $today = Jalalian::fromCarbon(Carbon::today());
            $year ??= $today->getYear();
            $month ??= $today->getMonth();

            $this->setRangeFromJalali($year, $month);

            return;
        }

        $today = Carbon::today();
        $year ??= $today->year;
        $month ??= $today->month;

        $this->setRangeFromGregorian($year, $month);
    }

    protected function shiftCalendarMonth(int $months): void
    {
        if ($months === 0) {
            return;
        }

        if ($this->calendarType === 'jalali') {
            $current = Jalalian::fromCarbon($this->startsAt);
            $target = $months > 0 ? $current->addMonths($months) : $current->subMonths(abs($months));
            $this->setRangeFromJalali($target->getYear(), $target->getMonth());

            return;
        }

        $this->startsAt = ($months > 0
            ? $this->startsAt->clone()->addMonths($months)
            : $this->startsAt->clone()->subMonths(abs($months)))
            ->startOfMonth()
            ->startOfDay();

        $this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();

        $this->calculateGridStartsEnds();
    }

    protected function setRangeFromGregorian(int $year, int $month): void
    {
        $this->startsAt = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();

        $this->calculateGridStartsEnds();

        if ($this->viewMode === 'month' || $this->currentCursorDate === null) {
            $this->currentCursorDate = $this->startsAt->clone();
        }
    }

    protected function setRangeFromJalali(int $year, int $month): void
    {
        $timezone = $this->timezone();
        $start = new Jalalian($year, $month, 1, 0, 0, 0, $timezone);
        $this->startsAt = $start->toCarbon()->startOfDay();

        $end = new Jalalian($year, $month, $start->getMonthDays(), 0, 0, 0, $timezone);
        $this->endsAt = $end->toCarbon()->startOfDay();

        $this->calculateGridStartsEnds();
    }

    protected function currentMonthLabel(): string
    {
        return match ($this->viewMode) {
            'week' => $this->formatRangeForCalendar(...$this->currentWeekRange()),
            'day' => $this->formatDateForCalendar(
                $this->currentCursor(),
                'l, F j, Y',
                '%A %d %B %Y'
            ),
            'list' => $this->calendarType  === 'jalali' ? 'لیست رویدادها' : 'Event list',
            default => $this->calendarType === 'jalali'
                ? sprintf(
                    '%s %d',
                    Jalalian::fromCarbon($this->startsAt)->format('%B'),
                    Jalalian::fromCarbon($this->startsAt)->getYear()
                )
                : $this->startsAt->translatedFormat('F Y'),
        };
    }

    protected function currentMonthSubtitle(): string
    {
        return match ($this->viewMode) {
            'week' => $this->calendarType  === 'jalali' ? 'نمای هفتگی' : 'Week view',
            'day' => $this->calendarType   === 'jalali' ? 'نمای روزانه' : 'Day view',
            'list' => $this->calendarType  === 'jalali' ? 'نمای لیستی' : 'List view',
            default => $this->calendarType === 'jalali' ? 'نمای ماهانه' : 'Month view',
        };
    }

    protected function weekDays(): Collection
    {
        $start = $this->currentCursor()->clone()->startOfWeek($this->weekStartsAt);

        return collect(range(0, 6))->map(fn ($offset) => $start->clone()->addDays($offset));
    }

    protected function currentWeekRange(): array
    {
        $start = $this->currentCursor()->clone()->startOfWeek($this->weekStartsAt);
        $end = $start->clone()->endOfWeek($this->weekEndsAt);

        return [$start, $end];
    }

    protected function groupEventsByDate(Collection $events): Collection
    {
        return $events
            ->sortBy(fn ($event) => Carbon::parse($event['date']))
            ->groupBy(fn ($event) => Carbon::parse($event['date'])->toDateString());
    }

    protected function formatDateForCalendar(Carbon $date, string $gregorianFormat, string $jalaliFormat): string
    {
        if ($this->calendarType === 'jalali') {
            return Jalalian::fromCarbon($date)->format($jalaliFormat);
        }

        return $date->translatedFormat($gregorianFormat);
    }

    protected function formatRangeForCalendar(Carbon $start, Carbon $end): string
    {
        if ($this->calendarType === 'jalali') {
            $startJ = Jalalian::fromCarbon($start);
            $endJ = Jalalian::fromCarbon($end);

            return sprintf('%s - %s', $startJ->format('%e %B'), $endJ->format('%e %B %Y'));
        }

        return sprintf(
            '%s - %s',
            $start->translatedFormat('M j'),
            $end->translatedFormat('M j, Y')
        );
    }

    protected function applyWeekBoundaries(): void
    {
        if ($this->calendarType === 'jalali') {
            $this->weekStartsAt = Carbon::SATURDAY;
            $this->weekEndsAt = Carbon::FRIDAY;

            return;
        }

        $start = $this->customWeekStartsAt ?? Carbon::SUNDAY;
        $this->weekStartsAt = $start;
        $this->weekEndsAt = ($start + 6) % 7;
    }

    protected function timezone(): DateTimeZone
    {
        return new DateTimeZone(config('app.timezone', 'UTC'));
    }
}
