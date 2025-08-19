<?php

declare(strict_types=1);

namespace App\Actions\ContactUs;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\ContactUs;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateContactUsAction
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
    public function handle(ContactUs $contactUs, array $payload): ContactUs
    {
        return DB::transaction(function () use ($contactUs, $payload) {
            $contactUs->update($payload);

            return $contactUs->refresh();
        });
    }
}
