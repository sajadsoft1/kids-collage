<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class SearchPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.search-page')
            ->layout('components.layouts.web');
    }
}
