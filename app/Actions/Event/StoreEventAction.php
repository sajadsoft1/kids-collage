<?php

declare(strict_types=1);

namespace App\Actions\Event;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Event;
use App\Services\File\FileService;
use App\Services\SeoOption\SeoOptionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreEventAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly SeoOptionService $seoOptionService,
        private readonly FileService $fileService,
    ) {}

    /**
     * @param array{
     *     slug:string,
     *     title:string,
     *     description:string,
     *     body:string,
     *     published:bool,
     *     published_at:string,
     *     category_id:int,
     *     tags:array<string>,
     *     image:string,
     *     capacity:int,
     *     price:float,
     *     is_online:bool,
     *     start_date:string,
     *     end_date:string,
     *     location:string,
     *     extra_attributes:array,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Event
    {
        return DB::transaction(function () use ($payload) {
            $model = Event::create(Arr::only($payload, ['slug', 'published', 'published_at', 'category_id', 'capacity', 'price', 'is_online', 'start_date', 'end_date', 'location', 'extra_attributes']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'body']));
            $this->seoOptionService->create($model, Arr::only($payload, ['title', 'description']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));
            if ($tags = Arr::get($payload, 'tags')) {
                $model->syncTags($tags);
            }

            return $model->refresh();
        });
    }
}
