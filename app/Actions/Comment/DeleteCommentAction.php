<?php

declare(strict_types=1);

namespace App\Actions\Comment;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteCommentAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Comment $comment): bool
    {
        return DB::transaction(function () use ($comment) {
            return $comment->delete();
        });
    }
}
