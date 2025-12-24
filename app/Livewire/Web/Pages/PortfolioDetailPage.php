<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Models\PortFolio;
use App\Services\SeoBuilder;
use Livewire\Component;

class PortfolioDetailPage extends Component
{
    public PortFolio $portfolio;

    public function render()
    {
        SeoBuilder::create($this->portfolio)
            ->portfolio()
            ->apply();

        return view('livewire.web.pages.portfolio-detail-page')
            ->layout('components.layouts.web');
    }
}
