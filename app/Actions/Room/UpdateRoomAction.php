<?php

namespace App\Actions\Room;

use App\Actions\Translation\SyncTranslationAction;
use App\Models\Room;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateRoomAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}


    /**
     * @param Room $room
     * @param array{
     *     title:string,
     *     description:string
     * }               $payload
     * @return Room
     * @throws Throwable
     */
    public function handle(Room $room, array $payload): Room
    {
        return DB::transaction(function () use ($room, $payload) {
            $room->update($payload);
            $this->syncTranslationAction->handle($room, Arr::only($payload, ['title', 'description']));

            return $room->refresh();
        });
    }
}
