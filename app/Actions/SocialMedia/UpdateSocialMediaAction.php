<?php

declare(strict_types=1);

namespace App\Actions\SocialMedia;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\SocialMedia;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateSocialMediaAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly FileService $fileService,
    ) {}

    /**
     * @param array{
     *     title:string,
     *      link:string,
     *      ordering:int,
     *      position:string,
     *      published:bool,
     *      image:file,
     * }               $payload
     * @throws Throwable
     */
    public function handle(SocialMedia $socialMedia, array $payload): SocialMedia
    {
        return DB::transaction(function () use ($socialMedia, $payload) {
            $socialMedia->update($payload);
            $this->syncTranslationAction->handle($socialMedia, Arr::only($payload, ['title', 'description']));
            $this->fileService->addMedia($socialMedia, Arr::get($payload, 'image'));

            return $socialMedia->refresh();
        });
    }
}
