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
            return $page->delete();
        });
    }
}
