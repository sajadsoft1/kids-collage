<?php

declare(strict_types=1);

namespace App\Actions\Discount;

use App\Models\Discount;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteDiscountAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Discount $discount): bool
    {
        return DB::transaction(function () use ($discount) {
            return $discount->delete();
        });
    }
}
