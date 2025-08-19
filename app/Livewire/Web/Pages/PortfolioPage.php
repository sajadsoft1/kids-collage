<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class PortfolioPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.portfolio-page')
            ->layout('components.layouts.web');
    }
}
