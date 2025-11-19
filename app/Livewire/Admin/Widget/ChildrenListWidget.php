<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widget;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Isolate;
use Livewire\Component;

#[Isolate]
class ChildrenListWidget extends Component
{
    public int $limit = 10;

    public ?int $parent_id = null;

    public ?Collection $children = null;

    /** Initialize the widget with default values */
    public function mount(
        int $limit = 10,
        ?int $parent_id = null,
        ?Collection $children = null
    ): void {
        $this->limit = $limit;
        $this->parent_id = $parent_id ?? auth()->id();
        $this->children = $children;
    }

    /** Get the list of children */
    #[Computed]
    public function childrenList()
    {
        if ($this->children) {
            return $this->children->take($this->limit);
        }

        if ($this->parent_id) {
            $parent = User::with(['children.profile'])->find($this->parent_id);

            return $parent?->children->take($this->limit) ?? collect();
        }

        return collect();
    }

    /** Get children statistics */
    #[Computed]
    public function childrenStats(): array
    {
        $children = $this->childrenList();

        return [
            'total' => $children->count(),
            'with_active_enrollments' => $children->filter(function ($child) {
                return $child->enrollments()->where('status', 'active')->exists();
            })->count(),
        ];
    }

    /** Get the URL for viewing more items */
    public function getMoreItemsUrl(): string
    {
        $params = http_build_query(array_filter([
            'parent_id' => $this->parent_id,
        ]));

        return route('admin.user.index') . '?' . $params;
    }

    /** Render the component */
    public function render()
    {
        return view('livewire.admin.widget.children-list-widget');
    }
}
