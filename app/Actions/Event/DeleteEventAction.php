<?php

declare(strict_types=1);

namespace App\Actions\Event;

use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteEventAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Event $event): bool
    {
        return DB::transaction(function () use ($event) {
            return $event->delete();
        });
    }
}
