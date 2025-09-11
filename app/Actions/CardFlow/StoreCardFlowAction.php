<?php

declare(strict_types=1);

namespace App\Actions\CardFlow;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\CardFlow;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreCardFlowAction
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
     * @throws Throwable
     */
    public function handle(array $payload): CardFlow
    {
        return DB::transaction(function () use ($payload) {
            $model =  CardFlow::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
