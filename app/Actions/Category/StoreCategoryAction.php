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
     *     parent_id:string,
     *     ordering:int,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Category
    {
        return DB::transaction(function () use ($payload) {
            $model = Category::create(Arr::only($payload, ['slug', 'published', 'type', 'ordering', 'parent_id']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'body']));
            $this->seoOptionService->create($model, Arr::only($payload, ['title', 'description']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));

            return $model->refresh();
        });
    }
}
