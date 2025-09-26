<?php

declare(strict_types=1);

namespace App\Actions\Bulletin;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Bulletin;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateBulletinAction
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
     *     published:bool,
     *     published_at:?string,
     *     user_id:int,
     *     category_id:int,
     *     view_count:int,
     *     comment_count:int,
     *     wish_count:int,
     *     languages:array
     * }               $payload
     * @throws Throwable
     */
    public function handle(Bulletin $bulletin, array $payload): Bulletin
    {
        return DB::transaction(function () use ($bulletin, $payload) {
            $bulletin->update(Arr::only($payload, [
                'published',
                'published_at',
                'user_id',
                'category_id',
                'view_count',
                'comment_count',
                'wish_count',
                'languages',
            ]));
            $this->syncTranslationAction->handle($bulletin, Arr::only($payload, ['title', 'description', 'body']));

            return $bulletin->refresh();
        });
    }
}
