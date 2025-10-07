<?php

declare(strict_types=1);

namespace App\Actions\Bulletin;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Bulletin;
use App\Services\File\FileService;
use App\Services\SeoOption\SeoOptionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreBulletinAction
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
     *     published_at:?string,
     *     user_id:int,
     *     category_id:int,
     *     view_count:int,
     *     comment_count:int,
     *     wish_count:int,
     *     languages:array,
     *     slug:string
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Bulletin
    {
        return DB::transaction(function () use ($payload) {
            $model = Bulletin::create(Arr::only($payload, [
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
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'body']));
            $this->seoOptionService->create($model, Arr::only($payload, ['title', 'description']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));
            if ($tags = Arr::get($payload, 'tags')) {
                $model->syncTagsWithType($tags, 'bulletin');
            }

            return $model->refresh();
        });
    }
}
