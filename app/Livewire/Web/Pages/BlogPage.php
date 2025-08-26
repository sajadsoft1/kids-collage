<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Models\Blog;
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

        return view('livewire.web.pages.blog-page', [
            'blogs' => Blog::query()
                ->where('published', 1)
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ])
            ->layout('components.layouts.web');
    }
}
