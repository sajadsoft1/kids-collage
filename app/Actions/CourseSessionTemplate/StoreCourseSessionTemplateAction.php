<?php

declare(strict_types=1);

namespace App\Actions\CourseSessionTemplate;

use App\Actions\Translation\SyncTranslationAction;
use App\Enums\CourseTypeEnum;
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
    ) {}

    /**
     * @param array{
     *     course_template_id:int,
     *     order:int,
     *     duration_minutes:int,
     *     type?:string,
     *     title:string,
     *     description:string,
     *     body:string,
     *     image:string|null,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): CourseSessionTemplate
    {
        return DB::transaction(function () use ($payload) {
            $model = CourseSessionTemplate::create([
                'course_template_id' => $payload['course_template_id'],
                'order'              => $payload['order'] ?? 1,
                'duration_minutes'   => $payload['duration_minutes'],
                'type'               => $payload['type'] ?? CourseTypeEnum::IN_PERSON->value,
            ]);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'body']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));

            return $model->refresh();
        });
    }
}
