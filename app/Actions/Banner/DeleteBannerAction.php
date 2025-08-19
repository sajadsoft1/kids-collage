<?php

declare(strict_types=1);

namespace App\Actions\Banner;

use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteBannerAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Banner $banner): bool
    {
        return DB::transaction(function () use ($banner) {
            return $banner->delete();
        });
    }
}
