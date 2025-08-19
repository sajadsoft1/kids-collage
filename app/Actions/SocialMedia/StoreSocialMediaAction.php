<?php

declare(strict_types=1);

namespace App\Actions\SocialMedia;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\SocialMedia;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreSocialMediaAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly FileService $fileService,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     link:string,
     *     ordering:int,
     *     position:string,
     *     published:bool,
     *     image:file,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): SocialMedia
    {
        return DB::transaction(function () use ($payload) {
            $model =  SocialMedia::create(Arr::except($payload, ['title']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));

            return $model->refresh();
        });
    }
}
