<?php

declare(strict_types=1);

namespace App\Actions\Resource;

use App\Models\Resource;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteResourceAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Resource $resource): bool
    {
        return DB::transaction(function () use ($resource) {
            return $resource->delete();
        });
    }
}
