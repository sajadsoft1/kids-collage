<?php

namespace App\Actions\Order;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateOrderAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Order $order
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Order
     * @throws Throwable
     */
    public function handle(Order $order, array $payload): Order
    {
        return DB::transaction(function () use ($order, $payload) {
            $order->update($payload);
            $this->syncTranslationAction->handle($order, Arr::only($payload, ['title', 'description']));

            return $order->refresh();
        });
    }
}
