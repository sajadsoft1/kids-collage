<?php

declare(strict_types=1);

namespace App\Actions\ContactUs;

use App\Actions\Translation\SyncTranslationAction;
use App\Enums\SmsTemplateEnum;
use App\Models\ContactUs;
use App\Services\Sms\SmsManager;
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
            $model = ContactUs::create($payload)->refresh();
            // app(SmsManager::class)->send(SmsTemplateEnum::CONTACT_US_CREATED, $model);

            return $model;
        });
    }
}
