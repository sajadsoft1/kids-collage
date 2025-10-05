<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\UserView;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasView
{
    public function views(): MorphMany
    {
        return $this->morphMany(UserView::class, 'morphable');
    }

    public function readByUser(): bool
    {
        if (auth()->check()) {
            return $this->views()->where('user_id', auth()->id())->exists();
        }

        return $this->views()->where('ip', request()->ip())->exists();
    }

    public function recordView(): void
    {
        $exists = $this->views()
            ->when(auth()->check(), function ($query) {
                $query->where('user_id', auth()->id());
            }, function ($query) {
                $query->where('ip', request()->ip());
            })
            ->exists();

        if ( ! $exists) {
            $this->views()->create([
                'user_id'    => auth()->id(),
                'ip'         => request()->ip(),
                'collection' => 'website',
            ]);
            $this->update([
                'view_count' => ++$this->view_count,
            ]);
        }
    }
}
