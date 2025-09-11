<?php

declare(strict_types=1);

namespace App\Actions\CardFlow;

use App\Models\CardFlow;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteCardFlowAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(CardFlow $cardFlow): bool
    {
        return DB::transaction(function () use ($cardFlow) {
            return $cardFlow->delete();
        });
    }
}
