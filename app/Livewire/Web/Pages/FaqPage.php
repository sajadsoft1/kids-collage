<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class FaqPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.faq-page')
            ->layout('components.layouts.web');
    }
}
