<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class NewsDetailPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.news-detail-page')
            ->layout('components.layouts.web');
    }
}
