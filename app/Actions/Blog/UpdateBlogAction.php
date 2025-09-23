<?php

declare(strict_types=1);

namespace App\Actions\Blog;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Blog;
use App\Services\File\FileService;
use App\Services\SeoOption\SeoOptionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateBlogAction
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
     *     body:string,
     *     published:bool,
     *     published_at:string,
     *     category_id:int,
     *     tags:array<string>,
     *     image:string,
     * } $payload
     * @throws Throwable
     */
    public function handle(Blog $blog, array $payload): Blog
    {
        return DB::transaction(function () use ($blog, $payload) {
            $blog->update(Arr::only($payload, ['published', 'published_at', 'category_id']));
            $this->syncTranslationAction->handle($blog, Arr::only($payload, ['title', 'description', 'body']));
            $this->fileService->addMedia($blog, Arr::get($payload, 'image'));
            $blog->syncTags(Arr::get($payload, 'tags', []));

            return $blog->refresh();
        });
    }
}
