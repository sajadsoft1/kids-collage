<?php

declare(strict_types=1);

namespace App\Traits;


use Illuminate\Http\Request;

trait HasViewTracking
{
    public function recordView($model,$collection=null): void
    {

        $exists = $model->views()
            ->where('ip', request()->ip())
            ->exists();

        if (! $exists) {
            $model->views()->create([
                'user_id'    => auth()->id()??null,
                'ip'         => request()->ip(),
                'collection'=>$collection
            ]);
            $model->update([
                'view_count'=>++$model->view_count
            ]);
        }
    }
}
