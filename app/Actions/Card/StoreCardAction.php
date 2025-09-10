<?php

namespace App\Actions\Card;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Card;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreCardAction
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
     * @return Card
     * @throws Throwable
     */
    public function handle(array $payload): Card
    {
        return DB::transaction(function () use ($payload) {
            $model =  Card::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
