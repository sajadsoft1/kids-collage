<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StorePaymentAction
{
    use AsAction;

    /**
     * @param array{
     *     user_id:int,
     *     order_id:int,
     *     amount:float,
     *     type:string,
     *     status:string,
     *     transaction_id:string|null
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Payment
    {
        return DB::transaction(function () use ($payload) {
            $model = Payment::create($payload);

            return $model->refresh();
        });
    }
}
