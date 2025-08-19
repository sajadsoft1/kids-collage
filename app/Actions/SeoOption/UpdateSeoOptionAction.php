<?php

declare(strict_types=1);

namespace App\Actions\SeoOption;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\SeoOption;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateSeoOptionAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @throws Throwable
     */
    public function handle(SeoOption $seoOption, array $payload): SeoOption
    {
        return DB::transaction(function () use ($seoOption, $payload) {
            $seoOption->update($payload);
            $this->syncTranslationAction->handle($seoOption, Arr::only($payload, ['title', 'description']));

            return $seoOption->refresh();
        });
    }
}
