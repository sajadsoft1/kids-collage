<?php

declare(strict_types=1);

namespace App\Actions\Card;

use App\Models\Card;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteCardAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Card $card): bool
    {
        return DB::transaction(function () use ($card) {
            return $card->delete();
        });
    }
}
