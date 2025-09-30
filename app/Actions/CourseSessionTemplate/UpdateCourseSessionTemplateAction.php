<?php

declare(strict_types=1);

namespace App\Actions\CourseSessionTemplate;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\CourseSessionTemplate;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateCourseSessionTemplateAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly FileService $fileService,
    ) {}

    /**
     * @param array{
     *    order:int,
     *    duration_minutes:int,
     *    type?:string,
     *    title:string,
     *    description:string,
     *    body:string,
     *    image:string|null,
     *}                             $payload
     * @throws Throwable
     */
    public function handle(CourseSessionTemplate $courseSessionTemplate, array $payload): CourseSessionTemplate
    {
        return DB::transaction(function () use ($courseSessionTemplate, $payload) {
            $courseSessionTemplate->update([
                'order'            => $payload['order'] ?? 1,
                'duration_minutes' => $payload['duration_minutes'],
            ]);
            $this->syncTranslationAction->handle($courseSessionTemplate, Arr::only($payload, ['title', 'description', 'body']));
            $this->fileService->addMedia($courseSessionTemplate, Arr::get($payload, 'image'));

            return $courseSessionTemplate->refresh();
        });
    }
}
