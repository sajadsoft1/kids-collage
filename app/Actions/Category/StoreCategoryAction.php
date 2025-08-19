<?php

declare(strict_types=1);

namespace App\Actions\Category;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Category;
use App\Services\File\FileService;
use App\Services\SeoOption\SeoOptionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreCategoryAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly FileService $fileService,
        private readonly SeoOptionService $seoOptionService,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     published:bool,
     *     type:string,
     *     image:string,
     *     seo_title:string,
     *     seo_description:string,
     *     canonical:string,
     *     old_url:string,
     *     redirect_to:string,
     *     robots_meta:string,
     *     tags:array<string>,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Category
    {
        return DB::transaction(function () use ($payload) {
            $model = Category::create(Arr::only($payload, ['slug', 'published', 'parent_id', 'type', 'ordering']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'body']));
            $this->seoOptionService->create($model, Arr::only($payload, ['seo_title', 'seo_description', 'canonical', 'old_url', 'redirect_to', 'robots_meta']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));

            return $model->refresh();
        });
    }
}
