<?php

declare(strict_types=1);

namespace App\Actions\Room;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Room;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreRoomAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     capacity:int,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Room
    {
        return DB::transaction(function () use ($payload) {
            $model = Room::create(Arr::only($payload, ['capacity']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
