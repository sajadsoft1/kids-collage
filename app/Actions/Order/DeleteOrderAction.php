<?php

namespace App\Actions\Order;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteOrderAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Order $order): bool
    {
        return DB::transaction(function () use ($order) {
            return $order->delete();
        });
    }
}
