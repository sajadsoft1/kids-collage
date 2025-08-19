<?php

declare(strict_types=1);

namespace App\Actions\Teammate;

use App\Models\Teammate;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteTeammateAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Teammate $teammate): bool
    {
        return DB::transaction(function () use ($teammate) {
            return $teammate->delete();
        });
    }
}
