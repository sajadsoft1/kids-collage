<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widget;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Isolate;
use Livewire\Component;

#[Isolate]
class PopularBlogsWidget extends Component
{
    public int $limit          = 5;
    public ?string $start_date = null;
    public ?string $end_date   = null;

    /** Initialize the widget with default values */
    public function mount(int $limit = 5, ?string $start_date = null, ?string $end_date = null): void
    {
        $this->limit      = $limit;
        $this->start_date = $start_date ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $this->end_date   = $end_date ?? Carbon::now()->format('Y-m-d');
    }

    /** Get the popular blogs */
    #[Computed]
    public function popularBlogs()
    {
        $query = Blog::query()
            ->with(['user', 'category'])
            ->where('published', true)
            ->when($this->start_date, function (Builder $query) {
                $query->whereDate('created_at', '>=', $this->start_date);
            })
            ->when($this->end_date, function (Builder $query) {
                $query->whereDate('created_at', '<=', $this->end_date);
            })
            ->orderByDesc('view_count')
            ->orderByDesc('comment_count')
            ->orderByDesc('wish_count')
            ->limit($this->limit);

        return $query->get();
    }

    /** Get the total count of published blogs */
    #[Computed]
    public function totalPublishedBlogs()
    {
        $query = Blog::query()
            ->where('published', true)
            ->when($this->start_date, function (Builder $query) {
                $query->whereDate('created_at', '>=', $this->start_date);
            })
            ->when($this->end_date, function (Builder $query) {
                $query->whereDate('created_at', '<=', $this->end_date);
            });

        return $query->count();
    }

    /** Get the URL for viewing more items */
    public function getMoreItemsUrl(): string
    {
        $params = http_build_query([
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
        ]);

        return route('admin.blog.index') . '?' . $params;
    }

    /** Render the component */
    public function render()
    {
        return view('livewire.admin.widget.popular-blogs-widget');
    }
}
