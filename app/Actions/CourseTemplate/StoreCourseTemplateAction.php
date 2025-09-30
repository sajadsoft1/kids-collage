<?php

namespace App\Actions\CourseTemplate;

use App\Actions\CourseSessionTemplate\StoreCourseSessionTemplateAction;
use App\Actions\Translation\SyncTranslationAction;
use App\Enums\CourseType;
use App\Models\CourseTemplate;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreCourseTemplateAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
        private readonly StoreCourseSessionTemplateAction $storeCourseSessionTemplateAction,
        private readonly FileService $fileService,
    )
    {
    }

    /**
     * @param array{
     *     slug:string,
     *     category_id:int|null,
     *     level:string|null,
     *     prerequisites:array|null,
     *     is_self_paced:bool|null,
     *     type:string|null,
     *     title:string,
     *     description:string,
     *     body:string,
     *     tags:array<string>,
     *     image:string|null,
     *     sessions:array{
     *     order:int,
     *     duration_minutes:int,
     *     title:string,
     *     description:string,
     * }[],
     * } $payload
     * @return CourseTemplate
     * @throws Throwable
     */
    public function handle(array $payload): CourseTemplate
    {
        return DB::transaction(function () use ($payload) {
            $model = CourseTemplate::create([
                'slug'          => $payload['slug'],
                'category_id'   => $payload['category_id'] ?? null,
                'level'         => $payload['level'] ?? null,
                'prerequisites' => $payload['prerequisites'] ?? [],
                'is_self_paced' => $payload['is_self_paced'] ?? false,
                'type'          => $payload['type'] ?? CourseType::IN_PERSON->value,
            ]);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'body']));
            $this->fileService->addMedia($model, Arr::get($payload, 'image'));
            if ($tags = Arr::get($payload, 'tags')) {
                $model->syncTags($tags);
            }

            foreach ($payload['sessions'] as $session) {
                $this->storeCourseSessionTemplateAction->handle(array_merge($session, [
                    'course_template_id' => $model->id,
                ]));
            }

            return $model->refresh();
        });
    }
}
