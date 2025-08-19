<?php

declare(strict_types=1);

namespace App\Actions\SeoOption;

use App\Models\SeoOption;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteSeoOptionAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(SeoOption $seoOption): bool
    {
        return DB::transaction(function () use ($seoOption) {
            return $seoOption->delete();
        });
    }
}
