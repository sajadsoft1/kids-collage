<?php

declare(strict_types=1);

namespace App\Actions\CourseTemplateLevel;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\CourseTemplateLevel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreCourseTemplateLevelAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): CourseTemplateLevel
    {
        return DB::transaction(function () use ($payload) {
            $model = CourseTemplateLevel::create(Arr::only($payload, ['published', 'languages']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
