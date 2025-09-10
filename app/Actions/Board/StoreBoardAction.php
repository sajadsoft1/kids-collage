<?php

namespace App\Actions\Board;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Board;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreBoardAction
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
     * @return Board
     * @throws Throwable
     */
    public function handle(array $payload): Board
    {
        return DB::transaction(function () use ($payload) {
            $model =  Board::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
