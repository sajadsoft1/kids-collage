<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use App\Services\SeoBuilder;
use Livewire\Component;
use Livewire\WithPagination;

class BlogPage extends Component
{
    use WithPagination;

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
            'blogs'        => Blog::query()
                ->where('published', 1)
                ->orderBy('created_at', 'desc')
                ->paginate(10),
            'latest_blogs' => Blog::query()
                ->where('published', 1)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get(),
            'categories'   => Category::query()
                ->where('published', 1)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get(),
            'tags'         => Tag::query()
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get(),
        ])
            ->layout('components.layouts.web');
    }
}
