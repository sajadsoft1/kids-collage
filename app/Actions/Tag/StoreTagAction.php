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

class StoreTagAction
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
     *     description?: string,
     *     body?: string,
     *     type?: string,
     *     order_column?: int,
     *     image?: string,
     *     seo_title:string,
     *     seo_description:string,
     *     canonical:string,
     *     old_url:string,
     *     redirect_to:string,
     *     robots_meta:string,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Tag
    {
        return DB::transaction(function () use ($payload) {
            $defaultLanguage = app()->getLocale();
            $tag             = Tag::findOrCreate(Arr::get($payload, 'name'), locale: $defaultLanguage);
            $tag->update(['order_column' => Arr::get($payload, 'order_column', 1)]);
            $this->seoOptionService->create($tag, Arr::only($payload, ['title', 'description']));
            $this->syncTagTranslationAction->handle($tag, Arr::only($payload, ['description', 'body']));

            $this->fileService->addMedia($tag, Arr::get($payload, 'image'));

            return $tag->refresh();
        });
    }
}
