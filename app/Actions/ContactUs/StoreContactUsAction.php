<?php

declare(strict_types=1);

namespace App\Actions\ContactUs;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\ContactUs;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreContactUsAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     name:string,
     *     email:string,
     *     mobile:string,
     *     comment:string,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): ContactUs
    {
        return DB::transaction(function () use ($payload) {
            return ContactUs::create($payload)->refresh();
        });
    }
}
