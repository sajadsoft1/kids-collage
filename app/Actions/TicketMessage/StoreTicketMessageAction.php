<?php

declare(strict_types=1);

namespace App\Actions\TicketMessage;

use App\Models\TicketMessage;
use App\Services\File\FileService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreTicketMessageAction
{
    use AsAction;

    public function __construct(
        protected FileService $fileService,
    ) {}

    /** @param array{
     *     ticket_id:int,
     *     user_id:int,
     *     message:string,
     *     file:string,
     *     } $payload
     * @throws Throwable
     */
    public function handle(array $payload): TicketMessage
    {
        return DB::transaction(function () use ($payload) {
            $message = TicketMessage::create($payload)->refresh();
            $this->fileService->addMedia($message, Arr::get($payload, 'file'), 'gallery');

            return $message;
        });
    }
}
