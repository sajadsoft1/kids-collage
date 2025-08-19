<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Services\SeoBuilder;
use Livewire\Component;

class BlogPage extends Component
{
    public function render()
    {
        SeoBuilder::create()
            ->title(trans('web/blog.title'))
            ->description(trans('web/blog.description'))
            ->keywords(trans('web/blog.keywords'))
            ->images([asset('100_100.png')])
            ->canonical(url('/'))
            ->hreflangs([
                ['lang' => 'en', 'url' => url('/')],
                ['lang' => 'fa', 'url' => url('/fa')],
            ])
            ->apply();

        return view('livewire.web.pages.blog-page')
            ->layout('components.layouts.web');
    }
}
