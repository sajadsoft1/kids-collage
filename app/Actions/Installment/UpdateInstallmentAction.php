<?php

namespace App\Actions\Installment;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Installment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateInstallmentAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Installment $installment
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Installment
     * @throws Throwable
     */
    public function handle(Installment $installment, array $payload): Installment
    {
        return DB::transaction(function () use ($installment, $payload) {
            $installment->update($payload);
            $this->syncTranslationAction->handle($installment, Arr::only($payload, ['title', 'description']));

            return $installment->refresh();
        });
    }
}
