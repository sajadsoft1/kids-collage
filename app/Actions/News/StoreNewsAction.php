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

class StoreNewsAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly SeoOptionService $seoOptionService,
        private readonly FileService $fileService,
    ) {}

    /**
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
     * image:string
     * } $payload
     * @return News
     * @throws Throwable
     */
    public function handle(array $payload): News
    {
        return DB::transaction(function () use ($payload) {
            $payload['user_id'] = $payload['user_id']??auth()->user()->id;
            $model =  News::create(Arr::only($payload, [
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
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description','body']));
            $this->seoOptionService->create($model, Arr::only($payload, ['seo_title', 'seo_description', 'canonical', 'old_url', 'redirect_to', 'robots_meta']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));
            if ($tags = Arr::get($payload, 'tags')) {
                $model->syncTags($tags);
            }
            return $model->refresh();
        });
    }
}
