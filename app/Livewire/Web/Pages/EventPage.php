<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class EventPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.event-page')
            ->layout('components.layouts.web');
    }
}
