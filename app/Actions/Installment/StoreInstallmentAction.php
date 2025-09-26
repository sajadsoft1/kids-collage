<?php

declare(strict_types=1);

namespace App\Actions\Installment;

use App\Models\Installment;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreInstallmentAction
{
    use AsAction;

    /**
     * @param array{
     *     payment_id:int,
     *     amount:float,
     *     due_date:string,
     *     method:string,
     *     status:string,
     *     transaction_id:string|null
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Installment
    {
        return DB::transaction(function () use ($payload) {
            $model = Installment::create($payload);

            return $model->refresh();
        });
    }
}
