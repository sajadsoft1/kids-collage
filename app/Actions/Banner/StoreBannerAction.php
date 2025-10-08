<?php

declare(strict_types=1);

namespace App\Actions\Banner;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Banner;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreBannerAction
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
     *     published_at:string,
     *     size:string,
     *     image:string,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Banner
    {
        return DB::transaction(function () use ($payload) {
            $model =  Banner::create(Arr::only($payload, ['published', 'published_at', 'size', 'link']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));

            return $model->refresh();
        });
    }
}
