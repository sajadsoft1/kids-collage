<?php

declare(strict_types=1);

namespace App\Actions\Card;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Card;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateCardAction
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
    public function handle(Card $card, array $payload): Card
    {
        return DB::transaction(function () use ($card, $payload) {
            $card->update($payload);
            $this->syncTranslationAction->handle($card, Arr::only($payload, ['title', 'description']));

            return $card->refresh();
        });
    }
}
