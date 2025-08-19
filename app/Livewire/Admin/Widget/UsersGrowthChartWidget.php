<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widget;

use App\Models\Blog;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;

class UsersGrowthChartWidget extends Component
{
    public int $months = 6;

    /** Initialize the widget with default values */
    public function mount(int $months = 6): void
    {
        $this->months = $months;
    }

    /** Get monthly growth data for users, blogs, and tickets */
    #[Computed]
    public function monthlyGrowthData()
    {
        $data      = [];
        $startDate = Carbon::now()->subMonths($this->months - 1)->startOfMonth();

        for ($i = 0; $i < $this->months; $i++) {
            $monthStart = $startDate->copy()->addMonths($i);
            $monthEnd   = $monthStart->copy()->endOfMonth();
            $monthName  = $monthStart->format('M');

            $data[$monthName] = [
                'users'   => User::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'blogs'   => Blog::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'tickets' => Ticket::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
            ];
        }

        return $data;
    }

    /** Render the component */
    public function render()
    {
        return view('livewire.admin.widget.users_growth_chart');
    }
}
