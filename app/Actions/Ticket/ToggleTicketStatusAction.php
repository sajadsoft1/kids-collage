<?php

declare(strict_types=1);

namespace App\Actions\Ticket;

use App\Enums\TicketStatusEnum;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class ToggleTicketStatusAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Ticket $ticket): bool
    {
        return DB::transaction(function () use ($ticket) {
            if ($ticket->status === TicketStatusEnum::OPEN) {
                return $ticket?->update([
                    'status'    => TicketStatusEnum::CLOSE->value,
                    'closed_by' => auth()->id(),
                ]);
            }

            return $ticket?->update([
                'status' => TicketStatusEnum::OPEN->value,
            ]);
        });
    }
}
