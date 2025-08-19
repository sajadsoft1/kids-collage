<?php

declare(strict_types=1);

namespace App\Actions\Opinion;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Opinion;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateOpinionAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     user_name:string,
     *     comment:string,
     *     company:string,
     *     published:boolean,
     *     ordering:int,
     *     published_at:string,
     *     image:string,
     * }               $payload
     * @throws Throwable
     */
    public function handle(Opinion $opinion, array $payload): Opinion
    {
        return DB::transaction(function () use ($opinion, $payload) {
            $opinion->update($payload);
            $this->syncTranslationAction->handle($opinion, Arr::only($payload, ['title', 'description']));

            return $opinion->refresh();
        });
    }
}
