<?php

declare(strict_types=1);

namespace App\Actions\TicketMessage;

use App\Models\TicketMessage;
use App\Repositories\TicketMessage\TicketMessageRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTicketMessageAction
{
    use AsAction;
    
    public function __construct(private readonly TicketMessageRepositoryInterface $repository) {}
    
    /** @param array{
     *     ticket_id:int,
     *     user_id:int,
     *     message:string,
     *     } $payload
     */
    public function handle(TicketMessage $ticketMessage, array $payload): TicketMessage
    {
        return DB::transaction(function () use ($ticketMessage, $payload) {
            $this->repository->update($ticketMessage, $payload);
            
            return $ticketMessage->refresh();
        });
    }
}
