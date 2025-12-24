<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Models\Event;
use App\Services\SeoBuilder;
use Livewire\Component;

class EventDetailPage extends Component
{
    public Event $event;

    public function render()
    {
        SeoBuilder::create($this->event)
            ->event()
            ->apply();

        return view('livewire.web.pages.event-detail-page')
            ->layout('components.layouts.web');
    }
}
