<?php

declare(strict_types=1);

namespace App\Livewire\Web\Pages;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use App\Services\SeoBuilder;
use Livewire\Component;

class BlogDetailPage extends Component
{
    public Blog $blog;

    public function render()
    {
        SeoBuilder::create($this->blog)
            ->blog()
            ->apply();

        return view('livewire.web.pages.blog-detail-page', [
            'latest_blogs' => Blog::query()
                ->where('published', 1)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get(),
            'categories' => Category::query()
                ->where('published', 1)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get(),
            'tags' => Tag::query()
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get(),
        ])
            ->layout('components.layouts.web');
    }
}
