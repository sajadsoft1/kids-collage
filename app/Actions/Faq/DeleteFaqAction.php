<?php

declare(strict_types=1);

namespace App\Actions\Faq;

use App\Models\Faq;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteFaqAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Faq $faq): bool
    {
        return DB::transaction(function () use ($faq) {
            return $faq->delete();
        });
    }
}
