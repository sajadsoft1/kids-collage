<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use App\Actions\Translation\SyncTagTranslationAction;
use App\Models\Tag;
use App\Services\File\FileService;
use App\Services\SeoOption\SeoOptionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateTagAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTagTranslationAction $syncTagTranslationAction,
        private readonly SeoOptionService $seoOptionService,
        private readonly FileService $fileService,
    ) {}

    /**
     * @param array{
     *     name: string,
     *     slug: string,
     *     description?:string,
     *     body?:string,
     *     type?: string,
     *     order_column?: int,
     *     seo_title:string,
     *     seo_description:string,
     *     canonical:string,
     *     old_url:string,
     *     redirect_to:string,
     *     robots_meta:string,
     *     image?: string,
     * }          $payload
     * @throws Throwable
     */
    public function handle(Tag $tag, array $payload): Tag
    {
        return DB::transaction(function () use ($tag, $payload) {
            $defaultLanguage = app()->getLocale();
            $tag->setTranslation('name', $defaultLanguage, Arr::get($payload, 'name'));
            $tag->update(Arr::only($payload, ['slug', 'type', 'order_column']));
            $this->seoOptionService->update($tag, Arr::only($payload, ['seo_title', 'seo_description', 'canonical', 'old_url', 'redirect_to', 'robots_meta']));
            $this->syncTagTranslationAction->handle($tag, Arr::only($payload, ['description', 'body']));
            $this->fileService->addMedia($tag, Arr::get($payload, 'image'));

            return $tag->refresh();
        });
    }
}
