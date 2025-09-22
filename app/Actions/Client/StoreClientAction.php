<?php

declare(strict_types=1);

namespace App\Actions\Client;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Client;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreClientAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly FileService $fileService,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     published:bool,
     *     link:string,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Client
    {
        return DB::transaction(function () use ($payload) {
            $model =  Client::create(Arr::except($payload, ['title', 'image']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));

            return $model->refresh();
        });
    }
}
