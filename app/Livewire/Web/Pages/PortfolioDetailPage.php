<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class PortfolioDetailPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.portfolio-detail-page')
            ->layout('components.layouts.web');
    }
}
