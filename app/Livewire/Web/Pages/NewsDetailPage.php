<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Models\Bulletin;
use App\Services\SeoBuilder;
use Livewire\Component;

class NewsDetailPage extends Component
{
    public Bulletin $news;

    public function render()
    {
        SeoBuilder::create($this->news)
            ->news()
            ->apply();

        return view('livewire.web.pages.news-detail-page')
            ->layout('components.layouts.web');
    }
}
