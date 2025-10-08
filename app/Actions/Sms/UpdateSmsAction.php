<?php

declare(strict_types=1);

namespace App\Actions\Sms;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Sms;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateSmsAction
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
    public function handle(Sms $sms, array $payload): Sms
    {
        return DB::transaction(function () use ($sms, $payload) {
            $sms->update($payload);
            $this->syncTranslationAction->handle($sms, Arr::only($payload, ['title', 'description']));

            return $sms->refresh();
        });
    }
}
