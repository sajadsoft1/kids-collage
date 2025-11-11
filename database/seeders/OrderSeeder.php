<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Order\StoreOrderCourseAction;
use App\Enums\UserTypeEnum;
use App\Models\User;
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

        foreach ($data['order_course'] as $row) {
            StoreOrderCourseAction::run([
                'user_id' => User::where('type', UserTypeEnum::USER->value)->inRandomOrder()->first()->id,
                'discount_id' => $row['discount_id'] ?? null,
                'type' => $row['type'],
                'status' => $row['status'],
                'note' => $row['note'] ?? null,
                'items' => $row['items'] ?? [],
                'payments' => $row['payments'] ?? [],
                'force' => true, // Allow duplicate enrollments for seeding
            ]);
        }
    }
}
