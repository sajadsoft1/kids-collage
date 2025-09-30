<?php

namespace App\Actions\CourseSessionTemplate;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\CourseSessionTemplate;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreCourseSessionTemplateAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly FileService $fileService,

    )
    {
    }

    /**
     * @param array{
     *     course_template_id:int,
     *     order:int,
     *     duration_minutes:int,
     *     title:string,
     *     description:string,
     *     body:string,
     *     image:string|null,
     * } $payload
     * @return CourseSessionTemplate
     * @throws Throwable
     */
    public function handle(array $payload): CourseSessionTemplate
    {
        return DB::transaction(function () use ($payload) {
            $model = CourseSessionTemplate::create([
                'course_template_id' => $payload['course_template_id'],
                'order'              => $payload['order']??1,
                'duration_minutes'   => $payload['duration_minutes'],
            ]);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'body']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));
            return $model->refresh();
        });
    }
}
