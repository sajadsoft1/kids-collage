<?php

namespace App\Actions\Classroom;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Classroom;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateClassroomAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Classroom $classroom
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Classroom
     * @throws Throwable
     */
    public function handle(Classroom $classroom, array $payload): Classroom
    {
        return DB::transaction(function () use ($classroom, $payload) {
            $classroom->update($payload);
            $this->syncTranslationAction->handle($classroom, Arr::only($payload, ['title', 'description']));

            return $classroom->refresh();
        });
    }
}
