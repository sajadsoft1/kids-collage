<?php

namespace App\Actions\Branch;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Branch;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateBranchAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly FileService $fileService,
    ) {}


    /**
     * @param Branch $branch
     * @param array{
     *     title:string,
     *     description:string,
     *     address?:string,
     *     phone?:string,
     *     published:boolean,
 * }               $payload
     * @return Branch
     * @throws Throwable
     */
    public function handle(Branch $branch, array $payload): Branch
    {
        return DB::transaction(function () use ($branch, $payload) {
            $branch->update(Arr::except($payload,['title','description']));
            $this->syncTranslationAction->handle($branch, Arr::only($payload, ['title', 'description']));
            $this->fileService->addMedia($branch, Arr::get($payload, 'image'));
            return $branch->refresh();
        });
    }
}
