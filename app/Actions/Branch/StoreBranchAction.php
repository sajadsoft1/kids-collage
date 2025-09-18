<?php

namespace App\Actions\Branch;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Branch;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreBranchAction
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
     *     address?:string,
     *     phone?:string,
     *     published:boolean,
     *
     * } $payload
     * @return Branch
     * @throws Throwable
     */
    public function handle(array $payload): Branch
    {
        return DB::transaction(function () use ($payload) {
            $model =  Branch::create(Arr::except($payload,['title','description']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));

            return $model->refresh();
        });
    }
}
