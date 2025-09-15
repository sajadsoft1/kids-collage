<?php

declare(strict_types=1);

namespace App\Actions\Comment;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Comment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreCommentAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     published:boolean,
     *     user_id:int,
     *     admin_id:int,
     *     parent_id:int,
     *     comment:string,
     *     admin_note:string,
     *     morphable_type:string,
     *     morphable_id:int,
     *     published_at:string,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Comment
    {
        return DB::transaction(function () use ($payload) {
            $payload['user_id']=auth()->user()->id;
            $model =  Comment::create(Arr::except($payload, ['title', 'description']));
            return $model->refresh();
        });
    }
}
