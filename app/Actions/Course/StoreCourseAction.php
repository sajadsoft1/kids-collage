<?php

declare(strict_types=1);

namespace App\Actions\Course;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Course;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreCourseAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     body:string,
     *     teacher_id:int,
     *     category_id:int,
     *     price:float,
     *     type:string,
     *     start_date:string,
     *     end_date:string,
     *     languages:array
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Course
    {
        return DB::transaction(function () use ($payload) {
            $model = Course::create(Arr::only($payload, ['teacher_id', 'category_id', 'price', 'type', 'start_date', 'end_date', 'languages']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'body']));

            return $model->refresh();
        });
    }
}
