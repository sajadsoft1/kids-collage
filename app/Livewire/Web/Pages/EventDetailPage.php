<?php

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class EventDetailPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.event-detail-page')
            ->layout('components.layouts.web');
    }
}
