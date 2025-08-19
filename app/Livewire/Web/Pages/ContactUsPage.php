<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class ContactUsPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.contact-us-page')
            ->layout('components.layouts.web');
    }
}
