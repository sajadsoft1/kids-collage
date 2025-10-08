<?php

declare(strict_types=1);

namespace App\Actions\Sms;

use App\Models\Sms;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteSmsAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Sms $sms): bool
    {
        return DB::transaction(function () use ($sms) {
            return $sms->delete();
        });
    }
}
