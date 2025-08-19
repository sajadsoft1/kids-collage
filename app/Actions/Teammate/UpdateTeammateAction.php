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

class UpdateTeammateAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly FileService $fileService,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * } $payload
     * @throws Throwable
     */
    public function handle(Teammate $teammate, array $payload): Teammate
    {
        return DB::transaction(function () use ($teammate, $payload) {
            $teammate->update($payload);
            $this->syncTranslationAction->handle($teammate, Arr::only($payload, ['title', 'description']));
            foreach (config('extra_enums.teammate') as $key => $item) {
                $teammate->extra_attributes->set($key, Arr::get($payload, $key));
            }
            $teammate->save();
            $this->fileService->addMedia($teammate, Arr::get($payload, 'image'));

            return $teammate->refresh();
        });
    }
}
