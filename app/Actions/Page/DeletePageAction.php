<?php

declare(strict_types=1);

namespace App\Actions\Page;

use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeletePageAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Page $page): bool
    {
        return DB::transaction(function () use ($page) {
            abort_if( ! $page->deletable, 401, trans('page.notifications.you_cannot_delete_this_page'));

            return $page->delete();
        });
    }
}
