<?php

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
     * @param Bulletin $bulletin
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Bulletin
     * @throws Throwable
     */
    public function handle(Bulletin $bulletin, array $payload): Bulletin
    {
        return DB::transaction(function () use ($bulletin, $payload) {
            $bulletin->update($payload);
            $this->syncTranslationAction->handle($bulletin, Arr::only($payload, ['title', 'description']));

            return $bulletin->refresh();
        });
    }
}
