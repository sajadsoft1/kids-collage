<?php

namespace App\Actions\News;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\News;
use App\Services\File\FileService;
use App\Services\SeoOption\SeoOptionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateNewsAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly SeoOptionService $seoOptionService,
        private readonly FileService $fileService,
    ) {}


    /**
     * @param News $news
     * @param array{
     * title:string,
     * description:string,
     * body:string,
     * published:boolean,
     * published_at:string,
     * category_id:int,
     * slug:string,
     * seo_title:string,
     * seo_description:string,
     * canonical?:string,
     * old_url?:string,
     * redirect_to?:string,
     * robots_meta:string,
     * tags:array<string>,
     * image:string,
     * } $payload
     * @throws Throwable
     */
    public function handle(News $news, array $payload): News
    {
        return DB::transaction(function () use ($news, $payload) {
            $news->update(Arr::only($payload, [
                'slug',
                'published',
                'published_at',
                'user_id',
                'category_id',
                'view_count',
                'comment_count',
                'wish_count',
                'languages',
            ]));
            $this->syncTranslationAction->handle($news, Arr::only($payload, ['title', 'description','body']));
            $this->fileService->addMedia($news, Arr::get($payload, 'image'));
            $this->seoOptionService->update($news, Arr::only($payload, ['seo_title', 'seo_description', 'canonical', 'old_url', 'redirect_to', 'robots_meta']));
            if ($tags = Arr::get($payload, 'tags')) {
                $news->syncTags($tags);
            }
            return $news->refresh();
        });
    }
}
