<?php

declare(strict_types=1);

namespace App\Actions\TicketMessage;

use App\Models\TicketMessage;
use App\Repositories\TicketMessage\TicketMessageRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteTicketMessageAction
{
    use AsAction;

    public function __construct(public readonly TicketMessageRepositoryInterface $repository) {}

    public function handle(TicketMessage $ticketMessage): bool
    {
        return DB::transaction(function () use ($ticketMessage) {
            return $this->repository->delete($ticketMessage);
        });
    }
}
