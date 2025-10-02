<?php

declare(strict_types=1);

namespace App\Actions\Term;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Term;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreTermAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *     start_date?:string,
     *     end_date?:string,
     *     status?:string,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Term
    {
        return DB::transaction(function () use ($payload) {
            $model =  Term::create(Arr::only($payload, ['start_date', 'end_date', 'status']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
