<?php

declare(strict_types=1);

namespace App\Actions\Ticket;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteTicketAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Ticket $ticket): bool
    {
        return DB::transaction(function () use ($ticket) {
            return $ticket->delete();
        });
    }
}
