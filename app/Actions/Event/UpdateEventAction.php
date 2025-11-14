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

class UpdateEventAction
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
     *      description:string,
     *      body:string,
     *      published:bool,
     *      published_at:string,
     *      category_id:int,
     *      tags:array<string>,
     *      image:string,
     *      capacity:int,
     *      price:float,
     *      is_online:bool,
     *      start_date:string,
     *      end_date:string,
     *      location:string,
     *      extra_attributes:array,
     * }               $payload
     * @throws Throwable
     */
    public function handle(Event $event, array $payload): Event
    {
        return DB::transaction(function () use ($event, $payload) {
            $event->update(Arr::only($payload, ['slug', 'published', 'published_at', 'category_id', 'capacity', 'price', 'is_online', 'start_date', 'end_date', 'location', 'extra_attributes']));
            $this->syncTranslationAction->handle($event, Arr::only($payload, ['title', 'description', 'body']));
            $this->fileService->addMedia($event, Arr::get($payload, 'image'));
            $event->syncTags(Arr::get($payload, 'tags', []));

            return $event->refresh();
        });
    }
}
