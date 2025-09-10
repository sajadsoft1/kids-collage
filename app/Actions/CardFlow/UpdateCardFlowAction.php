<?php

namespace App\Actions\CardFlow;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\CardFlow;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateCardFlowAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param CardFlow $cardFlow
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return CardFlow
     * @throws Throwable
     */
    public function handle(CardFlow $cardFlow, array $payload): CardFlow
    {
        return DB::transaction(function () use ($cardFlow, $payload) {
            $cardFlow->update($payload);
            $this->syncTranslationAction->handle($cardFlow, Arr::only($payload, ['title', 'description']));

            return $cardFlow->refresh();
        });
    }
}
