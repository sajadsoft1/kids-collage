<?php

declare(strict_types=1);

namespace App\Actions\License;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\License;
use App\Services\File\FileService;
use App\Services\SeoOption\SeoOptionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreLicenseAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly SeoOptionService $seoOptionService,
        private readonly FileService $fileService,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     languages:array,
     *     slug:string,
     *     image:string,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): License
    {
        return DB::transaction(function () use ($payload) {
            $model = License::create(Arr::only($payload, [
                'slug',
                'view_count',
                'languages',
            ]));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));
            $this->seoOptionService->create($model, Arr::only($payload, ['title', 'description']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));

            return $model->refresh();
        });
    }
}
