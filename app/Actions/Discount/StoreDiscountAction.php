<?php

declare(strict_types=1);

namespace App\Actions\Discount;

use App\Models\Discount;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class StoreDiscountAction
{
    use AsAction;

    /**
     * Store a new discount code
     *
     * @param array{
     *     code: string,
     *     type: string,
     *     value: float,
     *     user_id?: int|null,
     *     min_order_amount?: float,
     *     max_discount_amount?: float|null,
     *     usage_limit?: int|null,
     *     usage_per_user?: int,
     *     starts_at?: string|null,
     *     expires_at?: string|null,
     *     is_active?: bool,
     *     description?: string|null
     * } $payload
     * @throws Throwable
     */
    public function handle(array $payload): Discount
    {
        return DB::transaction(function () use ($payload) {
            // Ensure code is uppercase for consistency
            if (isset($payload['code'])) {
                $payload['code'] = strtoupper($payload['code']);
            }

            // Initialize used_count
            $payload['used_count'] = 0;

            $model = Discount::create($payload);

            return $model->refresh();
        });
    }
}
