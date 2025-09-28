<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Blog;

trait HasViewTracking
{
    public function recordView($model): void
    {
        /** @var Blog $model */
        $exists = $model->views()
            ->when(auth()->check(), function ($query) {
                $query->where('user_id', auth()->id());
            },function ($query){
                $query->where('ip', request()->ip());
            })
            ->whereDate('created_at', '!=', now()->toDateString())
            ->exists();

        if ( ! $exists) {
            $model->views()->create([
                'user_id'    => auth()->id(),
                'ip'         => request()->ip(),
                'collection' => 'website',
            ]);
            $model->update([
                'view_count' => ++$model->view_count,
            ]);
        }
    }
}
