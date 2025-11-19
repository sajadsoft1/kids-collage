<?php

declare(strict_types=1);

namespace App\Actions\FlashCard;

use App\Actions\Translation\SyncTranslationAction;
use App\Enums\BooleanEnum;
use App\Models\LeitnerBox;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class MovieInLeitnerBoxAction
{
    use AsAction;

    public function __construct(
        private readonly SyncTranslationAction $syncTranslationAction,
    ) {}

    /**
     * @param array{
     *     flash_card_id:int,
     *     correct:bool
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): LeitnerBox
    {
        return DB::transaction(function () use ($payload) {
            $user = auth()->user();
            $model = LeitnerBox::where('flash_card_id', $payload['flash_card_id'])->orderBy('id', 'desc')->first();
            abort_if($model?->finished->value == BooleanEnum::ENABLE->value, 403);
            if ( ! $payload['correct'] || ! $model) {
                $model = LeitnerBox::create([
                    'user_id' => $user->id,
                    'flash_card_id' => $payload['flash_card_id'],
                    'box' => 1,
                    'finished' => BooleanEnum::DISABLE,
                    'next_review_at' => now()->addDay(),
                    'last_review_at' => now(),
                ]);
            } else {
                switch ($model->box) {
                    case 1:
                        $newBox = 2;
                        $next_review_days = 1;
                        $finished = BooleanEnum::DISABLE;

                        break;
                    case 2:
                        $newBox = 3;
                        $next_review_days = 2;
                        $finished = BooleanEnum::DISABLE;

                        break;
                    case 3:
                        $newBox = 4;
                        $next_review_days = 4;
                        $finished = BooleanEnum::DISABLE;

                        break;
                    case 4:
                        $newBox = 5;
                        $next_review_days = 9;
                        $finished = BooleanEnum::DISABLE;

                        break;
                    case 5:
                        $newBox = 5;
                        $next_review_days = 14;
                        $finished = BooleanEnum::ENABLE;

                        break;
                    default:
                        $newBox = 1;
                        $next_review_days = 1;
                        $finished = BooleanEnum::DISABLE;
                }
                $model = LeitnerBox::create([
                    'user_id' => $user->id,
                    'flash_card_id' => $payload['flash_card_id'],
                    'box' => $newBox,
                    'next_review_at' => now()->addDays($next_review_days),
                    'last_review_at' => now(),
                    'finished' => $finished,
                ]);
            }

            return $model->refresh();
        });
    }
}
