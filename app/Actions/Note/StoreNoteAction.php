<?php

declare(strict_types=1);

namespace App\Actions\Note;

use App\Models\Note;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreNoteAction
{
    use AsAction;

    public function __construct(
    ) {}

    /**
     * @param array{
     *     body:string,
     *     question_id:int
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Note
    {
        return DB::transaction(function () use ($payload) {
            $payload['user_id'] = auth()->user()->id;
            $model = Note::create($payload);

            return $model;
        });
    }
}
