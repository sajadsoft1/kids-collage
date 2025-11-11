<?php

declare(strict_types=1);

namespace App\Actions\Ticket;

use App\Actions\TicketMessage\StoreTicketMessageAction;
use App\Models\Ticket;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreTicketAction
{
    use AsAction;

    public function __construct(
        private readonly FileService $fileService,
        private readonly StoreTicketMessageAction $ticketMessageAction
    ) {}

    /**
     * @param array{
     *     subject:string,
     *     body:string,
     *     user_id:int,
     *     department:string,
     *     priority:string,
     *     image?:string,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Ticket
    {
        return DB::transaction(function () use ($payload) {
            $model = Ticket::create(Arr::only($payload, [
                'subject',
                'department',
                'priority',
                'user_id',
            ]));

            $this->fileService->addMedia($model, Arr::get($payload, 'image'));
            $this->ticketMessageAction->handle([
                'ticket_id' => $model->id,
                'user_id' => Arr::get($payload, 'user_id'),
                'message' => Arr::get($payload, 'body'),
            ]);

            return $model->refresh();
        });
    }
}
