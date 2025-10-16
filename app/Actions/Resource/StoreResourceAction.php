<?php

declare(strict_types=1);

namespace App\Actions\Resource;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Resource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreResourceAction
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
    public function handle(array $payload): Resource
    {
        return DB::transaction(function () use ($payload) {
            $model =  Resource::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
