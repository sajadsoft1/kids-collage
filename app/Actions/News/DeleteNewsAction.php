<?php

namespace App\Actions\News;

use App\Models\News;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteNewsAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(News $news): bool
    {
        return DB::transaction(function () use ($news) {
            return $news->delete();
        });
    }
}
