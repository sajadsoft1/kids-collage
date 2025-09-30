<?php

declare(strict_types=1);

namespace App\Actions\Room;

use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreRoomAction
{
    use AsAction;

    public function __construct()
    {
    }

    /**
     * @param array{
     *     name:string,
     *     location:string,
     *     capacity:int,
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Room
    {
        return DB::transaction(function () use ($payload) {
            return Room::create($payload)->refresh();
        });
    }
}
