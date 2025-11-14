<?php

declare(strict_types=1);

namespace App\Actions\Ticket;

use App\Actions\TicketMessage\StoreTicketMessageAction;
use App\Actions\Translation\SyncTranslationAction;
use App\Enums\TicketStatusEnum;
use App\Models\Ticket;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateTicketAction
{
    use AsAction;

    public function __construct(
        private readonly StoreTicketMessageAction $ticketMessageAction,
        private readonly FileService $fileService,
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     subject:string,
     *     description:string,
     *     user_id:int,
     *     department:string,
     *     priority:string,
     *     status:string,
     *     reply:string,
     *     image?:string,
     * }               $payload
     * @throws Throwable
     */
    public function handle(Ticket $ticket, array $payload): Ticket
    {
        return DB::transaction(function () use ($ticket, $payload) {
            $ticket->update($payload);

            if ($ticket->status === TicketStatusEnum::CLOSE) {
                $ticket->update([
                    'closed_by' => auth()->id(),
                ]);
            }

            $this->fileService->addMedia($ticket, Arr::get($payload, 'image'));

            $this->ticketMessageAction->handle([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'message' => Arr::get($payload, 'reply'),
            ]);

            return $ticket->refresh();
        });
    }
}
