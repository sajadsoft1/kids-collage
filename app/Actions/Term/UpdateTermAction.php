<?php

namespace App\Actions\Term;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Term;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateTermAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Term $term
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Term
     * @throws Throwable
     */
    public function handle(Term $term, array $payload): Term
    {
        return DB::transaction(function () use ($term, $payload) {
            $term->update($payload);
            $this->syncTranslationAction->handle($term, Arr::only($payload, ['title', 'description']));

            return $term->refresh();
        });
    }
}
