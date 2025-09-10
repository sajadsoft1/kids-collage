<?php

namespace App\Actions\Board;

use App\Models\Board;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteBoardAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Board $board): bool
    {
        return DB::transaction(function () use ($board) {
            return $board->delete();
        });
    }
}
