<?php

namespace App\Actions\Bulletin;

use App\Models\Bulletin;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteBulletinAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Bulletin $bulletin): bool
    {
        return DB::transaction(function () use ($bulletin) {
            return $bulletin->delete();
        });
    }
}
