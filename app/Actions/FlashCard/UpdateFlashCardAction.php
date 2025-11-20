<?php

declare(strict_types=1);

namespace App\Actions\FlashCard;

use App\Models\FlashCard;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class UpdateFlashCardAction
{
    use AsAction;

    public function __construct(
    ) {}

    /**
     * @param array{
     *     title:string,
     *     front:string,
     *     back:string,
     *     favorite:bool
     * }               $payload
     * @throws Throwable
     */
    public function handle(FlashCard $flashCard, array $payload): FlashCard
    {
        return DB::transaction(function () use ($flashCard, $payload) {
            $flashCard->update($payload);

            return $flashCard->refresh();
        });
    }
}
