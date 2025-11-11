<?php

declare(strict_types=1);

namespace App\Actions\Teammate;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Teammate;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreTeammateAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly FileService $fileService,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     bio:string,
     *     position:string,
     *     birthday:string,
     *     published:string,
     *     image:string,
     *     bio_image:string,
     *     email:string,
     *      } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Teammate
    {
        return DB::transaction(function () use ($payload) {
            $model = Teammate::create(Arr::except($payload, ['title', 'description', 'bio', 'image']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'bio']));
            foreach (config('extra_enums.teammate') as $key => $item) {
                $model->extra_attributes->set($key, Arr::get($payload, $key));
            }
            $model->save();
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));

            //            $this->fileService->addMedia($model, 'bio_image', 'bio_image');
            return $model->refresh();
        });
    }
}
