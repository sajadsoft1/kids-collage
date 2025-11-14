<?php

namespace App\Actions\Event;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Event;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreEventAction
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
     * @return Event
     * @throws Throwable
     */
    public function handle(array $payload): Event
    {
        return DB::transaction(function () use ($payload) {
            $model =  Event::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
