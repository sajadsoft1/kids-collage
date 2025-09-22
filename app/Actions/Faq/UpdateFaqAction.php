<?php

declare(strict_types=1);

namespace App\Actions\Faq;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Faq;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateFaqAction
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
     * }               $payload
     * @throws Throwable
     */
    public function handle(Faq $faq, array $payload): Faq
    {
        return DB::transaction(function () use ($faq, $payload) {
            $faq->update($payload);
            $this->syncTranslationAction->handle($faq, Arr::only($payload, ['title', 'description']));

            return $faq->refresh();
        });
    }
}
