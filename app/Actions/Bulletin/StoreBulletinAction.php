<?php

declare(strict_types=1);

namespace App\Actions\Bulletin;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Bulletin;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreBulletinAction
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
    public function handle(array $payload): Bulletin
    {
        return DB::transaction(function () use ($payload) {
            $model = Bulletin::create(Arr::only($payload, ['published', 'languages']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
