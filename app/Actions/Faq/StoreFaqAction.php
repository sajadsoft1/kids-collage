<?php

declare(strict_types=1);

namespace App\Actions\Faq;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Faq;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreFaqAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string,
     *      published:bool,
     *      favorite:bool,
     *      ordering:int,
     *      category_id:int,
     *      published_at:string
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Faq
    {
        return DB::transaction(function () use ($payload) {
            $model =  Faq::create(Arr::except($payload, ['title', 'description']));
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
