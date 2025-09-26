<?php

namespace App\Actions\Session;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Session;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreSessionAction
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
     * @return Session
     * @throws Throwable
     */
    public function handle(array $payload): Session
    {
        return DB::transaction(function () use ($payload) {
            $model =  Session::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
