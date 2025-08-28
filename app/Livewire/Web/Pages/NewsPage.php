<?php

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class NewsPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.news-page')
            ->layout('components.layouts.web');
    }
}
