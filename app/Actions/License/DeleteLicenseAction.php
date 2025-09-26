<?php

namespace App\Actions\License;

use App\Models\License;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteLicenseAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(License $license): bool
    {
        return DB::transaction(function () use ($license) {
            return $license->delete();
        });
    }
}
