<?php

declare(strict_types=1);

namespace App\Actions\FlashCard;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\FlashCard;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreFlashCardAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     front:string,
     *     back:string,
     *     favorite:bool
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): FlashCard
    {
        return DB::transaction(function () use ($payload) {
            $model = FlashCard::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title']));

            return $model->refresh();
        });
    }
}
