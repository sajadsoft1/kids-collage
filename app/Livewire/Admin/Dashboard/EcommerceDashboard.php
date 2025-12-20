<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Dashboard;

use Illuminate\View\View;
use Livewire\Component;

class EcommerceDashboard extends Component
{
    public function render(): View
    {
        return view('livewire.admin.dashboard.ecommerce-dashboard')
            ->layout('components.layouts.frest');
    }
}
