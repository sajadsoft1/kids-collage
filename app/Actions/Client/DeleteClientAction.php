<?php

declare(strict_types=1);

namespace App\Actions\Client;

use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteClientAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Client $client): bool
    {
        return DB::transaction(function () use ($client) {
            return $client->delete();
        });
    }
}
