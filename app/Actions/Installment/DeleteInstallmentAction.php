<?php

declare(strict_types=1);

namespace App\Actions\Installment;

use App\Models\Installment;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteInstallmentAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Installment $installment): bool
    {
        return DB::transaction(function () use ($installment) {
            return $installment->delete();
        });
    }
}
