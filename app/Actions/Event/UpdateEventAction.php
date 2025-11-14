<?php

namespace App\Actions\Event;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Event;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateEventAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Event $event
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Event
     * @throws Throwable
     */
    public function handle(Event $event, array $payload): Event
    {
        return DB::transaction(function () use ($event, $payload) {
            $event->update($payload);
            $this->syncTranslationAction->handle($event, Arr::only($payload, ['title', 'description']));

            return $event->refresh();
        });
    }
}
