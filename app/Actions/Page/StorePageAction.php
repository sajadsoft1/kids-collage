<?php

declare(strict_types=1);

namespace App\Actions\Page;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Page;
use App\Services\File\FileService;
use App\Services\SeoOption\SeoOptionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StorePageAction
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
     *     body:string,
     *     type:string,
     *     slug:string,
     *     seo_title:string,
     *     seo_description:string,
     *     canonical?:string,
     *     old_url?:string,
     *     redirect_to?:string,
     *     robots_meta:string,
     *     image?:string,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Page
    {
        return DB::transaction(function () use ($payload) {
            $model = Page::create(Arr::only($payload, ['type', 'slug']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'body']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));
            $this->seoOptionService->create($model, [
                'title'       => Arr::get($payload, 'title'),
                'description' => Arr::get($payload, 'body'),
            ]);

            return $model->refresh();
        });
    }
}
