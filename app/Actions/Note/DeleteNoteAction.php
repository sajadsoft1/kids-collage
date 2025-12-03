<?php

declare(strict_types=1);

namespace App\Actions\Note;

use App\Models\Note;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteNoteAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Note $note): bool
    {
        return DB::transaction(function () use ($note) {
            return $note->delete();
        });
    }
}
