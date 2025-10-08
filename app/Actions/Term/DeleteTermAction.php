<?php

declare(strict_types=1);

namespace App\Actions\Term;

use App\Models\Term;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteTermAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Term $term): bool
    {
        return DB::transaction(function () use ($term) {
            return $term->delete();
        });
    }
}
