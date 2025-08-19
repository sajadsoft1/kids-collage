<?php

declare(strict_types=1);

namespace App\Actions\ContactUs;

use App\Models\ContactUs;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteContactUsAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(ContactUs $contactUs): bool
    {
        return DB::transaction(function () use ($contactUs) {
            return $contactUs->delete();
        });
    }
}
