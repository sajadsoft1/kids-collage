<?php

namespace App\Actions\FlashCard;

use App\Models\FlashCard;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteFlashCardAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(FlashCard $flashCard): bool
    {
        return DB::transaction(function () use ($flashCard) {
            return $flashCard->delete();
        });
    }
}
