<?php

declare(strict_types=1);

namespace App\Actions\Opinion;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Opinion;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreOpinionAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly FileService $fileService,
    ) {}

    /**
     * @param array{
     *     user_name:string,
     *     comment:string,
     *     company?:string,
     *     published:bool,
     *     ordering:int,
     *     published_at:string,
     *     image:string,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Opinion
    {
        return DB::transaction(function () use ($payload) {
            $model = Opinion::create(Arr::except($payload, ['image']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'), 'image');
            $this->fileService->addMedia($model, Arr::get($payload, 'video'), 'video');

            return $model->refresh();
        });
    }
}
