<?php

declare(strict_types=1);

namespace App\Actions\Sms;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Sms;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreSmsAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     title:string,
     *     description:string
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Sms
    {
        return DB::transaction(function () use ($payload) {
            $model =  Sms::create($payload);
            $this->syncTranslationAction->handle($model, Arr::only($payload, ['title', 'description']));

            return $model->refresh();
        });
    }
}
