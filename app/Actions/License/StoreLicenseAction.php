<?php

declare(strict_types=1);

namespace App\Actions\License;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\License;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreLicenseAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     published:bool,
     *     languages:array
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): License
    {
        return DB::transaction(function () use ($payload) {
            $model = License::create(Arr::only($payload, ['published', 'languages']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
