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

class UpdateClientAction
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
     * }               $payload
     * @throws Throwable
     */
    public function handle(Client $client, array $payload): Client
    {
        return DB::transaction(function () use ($client, $payload) {
            $client->update($payload);
            $this->syncTranslationAction->handle($client, Arr::only($payload, ['title', 'description']));
            $this->fileService->addMedia($client, Arr::get($payload, 'image'));

            return $client->refresh();
        });
    }
}
