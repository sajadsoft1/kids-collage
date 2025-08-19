<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages\Services;

use Livewire\Component;

class WebsitePage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.services.website-page')
            ->layout('components.layouts.web');
    }
}
