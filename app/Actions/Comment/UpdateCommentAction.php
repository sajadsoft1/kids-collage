<?php

declare(strict_types=1);

namespace App\Actions\Comment;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Comment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateCommentAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     published:bool,
     *     user_id:int,
     *     admin_id:int,
     *     parent_id:int,
     *     comment:string,
     *     admin_note:string,
     *     morphable_type:string,
     *     morphable_id:int,
     *     published_at:string,
     * }               $payload
     * @throws Throwable
     */
    public function handle(Comment $comment, array $payload): Comment
    {
        return DB::transaction(function () use ($comment, $payload) {
            $comment->update($payload);
            $this->syncTranslationAction->handle($comment, Arr::only($payload, ['title', 'description']));

            return $comment->refresh();
        });
    }
}
