<?php

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class AboutUsPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.about-us-page')
            ->layout('components.layouts.web');
    }
}
