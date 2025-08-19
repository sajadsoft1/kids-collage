<?php

declare(strict_types=1);

namespace App\Actions\PortFolio;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\PortFolio;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class StorePortFolioAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly FileService $fileService,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description?:string,
     *     body?:string,
     *     published:bool,
     *     published_at:string,
     *     execution_date:string,
     *     category_id:int,
     *     creator_id?:int,
     *     image:string,
     *     tags?:array<string>,
     * } $payload
     */
    public function handle(array $payload): PortFolio
    {
        return DB::transaction(function () use ($payload) {
            $payload['creator_id'] = Arr::get($payload, 'creator_id') ?: auth()->id();

            /** @var PortFolio $model */
            $model = PortFolio::create(Arr::only($payload, ['category_id', 'creator_id', 'execution_date', 'published', 'published_at']));

            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'body']));

            if ($tags = Arr::get($payload, 'tags')) {
                $model->syncTags($tags);
            }

            $this->fileService->addMedia($model, Arr::get($payload, 'image'));

            return $model->refresh();
        });
    }
}
