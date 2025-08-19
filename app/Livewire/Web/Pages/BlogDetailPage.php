<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use Livewire\Component;

class BlogDetailPage extends Component
{
    public function render()
    {
        return view('livewire.web.pages.blog-detail-page')
            ->layout('components.layouts.web');
    }
}
