<?php

declare(strict_types=1);

namespace App\Actions\Resource;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Resource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateResourceAction
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
    public function handle(Resource $resource, array $payload): Resource
    {
        return DB::transaction(function () use ($resource, $payload) {
            $resource->update($payload);
            $this->syncTranslationAction->handle($resource, Arr::only($payload, ['title', 'description']));

            return $resource->refresh();
        });
    }
}
