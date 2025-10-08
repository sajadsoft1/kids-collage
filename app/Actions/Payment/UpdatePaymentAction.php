<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Payment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdatePaymentAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @throws Throwable
     */
    public function handle(Payment $payment, array $payload): Payment
    {
        return DB::transaction(function () use ($payment, $payload) {
            $payment->update($payload);
            $this->syncTranslationAction->handle($payment, Arr::only($payload, ['title', 'description']));

            return $payment->refresh();
        });
    }
}
