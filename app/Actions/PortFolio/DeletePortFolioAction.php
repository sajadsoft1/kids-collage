<?php

declare(strict_types=1);

namespace App\Actions\PortFolio;

use App\Models\PortFolio;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeletePortFolioAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(PortFolio $portFolio): bool
    {
        return DB::transaction(function () use ($portFolio) {
            return $portFolio->delete();
        });
    }
}
