<?php

declare(strict_types=1);

namespace App\Actions\Opinion;

use App\Models\Opinion;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteOpinionAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Opinion $opinion): bool
    {
        return DB::transaction(function () use ($opinion) {
            return $opinion->delete();
        });
    }
}
