<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widget;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Isolate;
use Livewire\Component;

#[Isolate]
class TopBlogWritersWidget extends Component
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

    /** Get the top blog writers */
    #[Computed]
    public function topWriters()
    {
        $query = User::query()
            ->withCount([
                'blogs as total_blogs' => function (Builder $query) {
                    $query->where('published', true);
                    $query->when($this->start_date, function (Builder $q) {
                        $q->whereDate('created_at', '>=', $this->start_date);
                    });
                    $query->when($this->end_date, function (Builder $q) {
                        $q->whereDate('created_at', '<=', $this->end_date);
                    });
                },
                'blogs as total_views' => function (Builder $query) {
                    $query->where('published', true);
                    $query->when($this->start_date, function (Builder $q) {
                        $q->whereDate('created_at', '>=', $this->start_date);
                    });
                    $query->when($this->end_date, function (Builder $q) {
                        $q->whereDate('created_at', '<=', $this->end_date);
                    });
                },
            ])
            ->withSum(
                [
                    'blogs as total_views_sum' => function (Builder $query) {
                        $query->where('published', true);
                        $query->when($this->start_date, function (Builder $q) {
                            $q->whereDate('created_at', '>=', $this->start_date);
                        });
                        $query->when($this->end_date, function (Builder $q) {
                            $q->whereDate('created_at', '<=', $this->end_date);
                        });
                    },
                ],
                'view_count',
            )
            ->having('total_blogs', '>', 0)
            ->orderByDesc('total_blogs')
            ->orderByDesc('total_views_sum')
            ->limit($this->limit);

        return $query->get();
    }

    /** Get the total count of writers */
    #[Computed]
    public function totalWriters()
    {
        $query = User::query()->whereHas('blogs', function (Builder $query) {
            $query->where('published', true);
            $query->when($this->start_date, function (Builder $q) {
                $q->whereDate('created_at', '>=', $this->start_date);
            });
            $query->when($this->end_date, function (Builder $q) {
                $q->whereDate('created_at', '<=', $this->end_date);
            });
        });

        return $query->count();
    }

    /** Get the URL for viewing more items */
    public function getMoreItemsUrl(): string
    {
        $params = http_build_query([
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        return route('admin.user.index') . '?' . $params;
    }

    /** Render the component */
    public function render()
    {
        return view('livewire.admin.widget.top-blog-writers-widget');
    }
}
