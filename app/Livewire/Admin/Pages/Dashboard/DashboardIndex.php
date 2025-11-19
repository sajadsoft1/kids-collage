<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Pages\Dashboard;

use App\Enums\UserTypeEnum;
use Carbon\Carbon;
use Livewire\Component;

class DashboardIndex extends Component
{
    public string $startDate;
    public string $endDate;
    public string $selectedPeriod = 'last_30_days';

    /** Listen for date range change events from header */
    protected $listeners = [
        'dateRangeChanged' => 'updatedDateRange',
    ];

    /** Initialize component with default date range */
    public function mount(): void
    {
        $this->setDefaultDateRange();
    }

    /** Set default date range (last 30 days) */
    private function setDefaultDateRange(): void
    {
        $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
    }

    /** Handle date range changes from header component */
    public function updatedDateRange($data): void
    {
        if (isset($data['startDate'], $data['endDate'])) {
            $this->startDate = $data['startDate'];
            $this->endDate = $data['endDate'];
            $this->selectedPeriod = $data['period'] ?? 'custom';

            // Dispatch event to update all widgets with new date range
            $this->dispatch('dashboardDateRangeUpdated', [
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'period' => $this->selectedPeriod,
            ]);
        }
    }

    /** Get the dashboard view name based on user type */
    private function getDashboardView(): string
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->guard('web')->user();

        if ( ! $user) {
            return 'livewire.admin.pages.dashboard.dashboard-employee';
        }

        return match ($user->type) {
            UserTypeEnum::USER => 'livewire.admin.pages.dashboard.dashboard-user',
            UserTypeEnum::PARENT => 'livewire.admin.pages.dashboard.dashboard-parent',
            UserTypeEnum::TEACHER => 'livewire.admin.pages.dashboard.dashboard-teacher',
            UserTypeEnum::EMPLOYEE => 'livewire.admin.pages.dashboard.dashboard-employee',
            default => 'livewire.admin.pages.dashboard.dashboard-employee',
        };
    }

    /** Render the dashboard with header and widgets */
    public function render()
    {
        return view($this->getDashboardView(), [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'selectedPeriod' => $this->selectedPeriod,
        ]);
    }
}
