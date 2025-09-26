<?php

namespace App\Actions\License;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\License;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateLicenseAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param License $license
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return License
     * @throws Throwable
     */
    public function handle(License $license, array $payload): License
    {
        return DB::transaction(function () use ($license, $payload) {
            $license->update($payload);
            $this->syncTranslationAction->handle($license, Arr::only($payload, ['title', 'description']));

            return $license->refresh();
        });
    }
}
