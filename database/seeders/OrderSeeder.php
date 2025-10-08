<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Order\StoreOrderAction;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeds sample order data with various statuses:
     * - Completed orders (payment received and processed)
     * - Processing orders (payment received, pending enrollment)
     * - Pending orders (awaiting payment)
     * - Cancelled orders (cancelled or failed)
     */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno_financial.php');

        foreach ($data['order'] as $row) {
            StoreOrderAction::run([
                'user_id'         => $row['user_id'],
                'discount_id'     => $row['discount_id'] ?? null,
                'pure_amount'     => $row['pure_amount'],
                'discount_amount' => $row['discount_amount'],
                'total_amount'    => $row['total_amount'],
                'status'          => $row['status'],
                'note'            => $row['note'] ?? null,
            ]);
        }
    }
}
