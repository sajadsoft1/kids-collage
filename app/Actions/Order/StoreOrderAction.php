<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreOrderAction
{
    use AsAction;

    /**
     * @param array{
     *     user_id:int,
     *     total_amount:float,
     *     status:string
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Order
    {
        return DB::transaction(function () use ($payload) {
            $model = Order::create($payload);

            return $model->refresh();
        });
    }
}
