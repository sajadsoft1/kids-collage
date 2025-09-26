<?php

namespace App\Actions\Payment;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Payment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StorePaymentAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * } $payload
     * @return Payment
     * @throws Throwable
     */
    public function handle(array $payload): Payment
    {
        return DB::transaction(function () use ($payload) {
            $model =  Payment::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
