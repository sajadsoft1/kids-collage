<?php

declare(strict_types=1);

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
     * @param array{
     *     title:string,
     *     description:string,
     *     languages:array
     * }               $payload
     * @throws Throwable
     */
    public function handle(License $license, array $payload): License
    {
        return DB::transaction(function () use ($license, $payload) {
            $license->update(Arr::only($payload, [
                'view_count',
                'languages',
            ]));
            $this->syncTranslationAction->handle($license, Arr::only($payload, ['title', 'description']));

            return $license->refresh();
        });
    }
}
