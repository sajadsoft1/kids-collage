<?php

declare(strict_types=1);

namespace App\Actions\Board;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Board;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateBoardAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @throws Throwable
     */
    public function handle(Board $board, array $payload): Board
    {
        return DB::transaction(function () use ($board, $payload) {
            $board->update($payload);
            $this->syncTranslationAction->handle($board, Arr::only($payload, ['title', 'description']));

            return $board->refresh();
        });
    }
}
