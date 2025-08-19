<?php

declare(strict_types=1);

namespace App\Actions\SocialMedia;

use App\Models\SocialMedia;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteSocialMediaAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(SocialMedia $socialMedia): bool
    {
        return DB::transaction(function () use ($socialMedia) {
            return $socialMedia->delete();
        });
    }
}
