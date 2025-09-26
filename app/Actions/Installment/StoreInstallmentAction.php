<?php

namespace App\Actions\Installment;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Installment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreInstallmentAction
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
     * @return Installment
     * @throws Throwable
     */
    public function handle(array $payload): Installment
    {
        return DB::transaction(function () use ($payload) {
            $model =  Installment::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
