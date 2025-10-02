<?php

declare(strict_types=1);

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
     * @param array{
     *     title:string,
     *     description:string
     *     start_date:string,
     *     end_date:string,
     *     status:string,
     * }               $payload
     * @throws Throwable
     */
    public function handle(Term $term, array $payload): Term
    {
        return DB::transaction(function () use ($term, $payload) {
            $term->update(Arr::only($payload, ['start_date', 'end_date', 'status']));
            $this->syncTranslationAction->handle($term, Arr::only($payload, ['title', 'description']));

            return $term->refresh();
        });
    }
}
