<?php

declare(strict_types=1);

namespace App\Actions\Note;

use App\Models\Note;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateNoteAction
{
    use AsAction;

    public function __construct(
    ) {}

    /**
     * @param array{
     *     body:string,
     *     question_id:int
     * }               $payload
     * @throws Throwable
     */
    public function handle(Note $note, array $payload): Note
    {
        return DB::transaction(function () use ($note, $payload) {
            $note->update($payload);

            return $note->refresh();
        });
    }
}
