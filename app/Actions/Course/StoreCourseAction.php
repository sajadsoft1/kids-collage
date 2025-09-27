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
     *     slug:string,
     *     published:bool,
     *     published_at:string,
     *     user_id:int,
     *     teacher_id:int,
     *     category_id:int,
     *     price:float,
     *     type:string,
     *     start_date:string,
     *     end_date:string,
     *     view_count:int,
     *     comment_count:int,
     *     wish_count:int,
     *     languages:array
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Course
    {
        return DB::transaction(function () use ($payload) {
            $model = Course::create(Arr::only($payload, [
                'slug', 'published', 'published_at', 'user_id', 'teacher_id', 'category_id',
                'price', 'type', 'start_date', 'end_date', 'view_count', 'comment_count',
                'wish_count', 'languages',
            ]));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description', 'body']));

            return $model->refresh();
        });
    }
}
