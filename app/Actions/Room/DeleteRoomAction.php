<?php

namespace App\Actions\Room;

use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteRoomAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Room $room): bool
    {
        return DB::transaction(function () use ($room) {
            return $room->delete();
        });
    }
}
