<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeletePaymentAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Payment $payment): bool
    {
        return DB::transaction(function () use ($payment) {
            return $payment->delete();
        });
    }
}
