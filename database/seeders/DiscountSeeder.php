<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Discount\StoreDiscountAction;
use App\Enums\DiscountTypeEnum;
use Illuminate\Database\Seeder;
use Throwable;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates comprehensive discount test data covering all scenarios:
     * - Percentage and fixed amount types
     * - Global and user-specific discounts
     * - Various validation conditions (limits, dates, minimums)
     */
    public function run(): void
    {
        $discounts = [
            // ============================================
            // PERCENTAGE DISCOUNTS
            // ============================================

            // 1. Basic Global Percentage Discount (Active)
            [
                'code'                => 'SAVE10',
                'type'                => DiscountTypeEnum::PERCENTAGE->value,
                'value'               => 10,
                'user_id'             => null,
                'min_order_amount'    => 0,
                'max_discount_amount' => null,
                'usage_limit'         => null,
                'usage_per_user'      => 999,
                'used_count'          => 0,
                'starts_at'           => now()->subDays(7),
                'expires_at'          => now()->addDays(30),
                'is_active'           => true,
                'description'         => 'Global 10% discount - No restrictions',
            ],

            // 2. User-Specific Percentage Discount (User ID = 1)
            [
                'code'                => 'USER1SPECIAL',
                'type'                => DiscountTypeEnum::PERCENTAGE->value,
                'value'               => 25,
                'user_id'             => 1,
                'min_order_amount'    => 0,
                'max_discount_amount' => null,
                'usage_limit'         => 10,
                'usage_per_user'      => 5,
                'used_count'          => 0,
                'starts_at'           => now()->subDays(7),
                'expires_at'          => now()->addDays(30),
                'is_active'           => true,
                'description'         => 'User-specific 25% discount for user #1',
            ],

            // 3. Percentage with Maximum Discount Cap
            [
                'code'                => 'SAVE20MAX',
                'type'                => DiscountTypeEnum::PERCENTAGE->value,
                'value'               => 20,
                'user_id'             => null,
                'min_order_amount'    => 100000,
                'max_discount_amount' => 50000,
                'usage_limit'         => 100,
                'usage_per_user'      => 3,
                'used_count'          => 0,
                'starts_at'           => now()->subDays(7),
                'expires_at'          => now()->addDays(60),
                'is_active'           => true,
                'description'         => '20% discount with 50,000 maximum cap and 100,000 minimum order',
            ],

            // 4. Percentage with Minimum Order Amount
            [
                'code'                => 'BIG15',
                'type'                => DiscountTypeEnum::PERCENTAGE->value,
                'value'               => 15,
                'user_id'             => null,
                'min_order_amount'    => 500000,
                'max_discount_amount' => null,
                'usage_limit'         => null,
                'usage_per_user'      => 999,
                'used_count'          => 0,
                'starts_at'           => now()->subDays(7),
                'expires_at'          => now()->addDays(90),
                'is_active'           => true,
                'description'         => '15% discount for orders above 500,000',
            ],

            // 5. Percentage with Usage Limit (Nearly Exhausted)
            [
                'code'                => 'LIMITED5',
                'type'                => DiscountTypeEnum::PERCENTAGE->value,
                'value'               => 5,
                'user_id'             => null,
                'min_order_amount'    => 0,
                'max_discount_amount' => null,
                'usage_limit'         => 10,
                'usage_per_user'      => 2,
                'used_count'          => 8,
                'starts_at'           => now()->subDays(30),
                'expires_at'          => now()->addDays(30),
                'is_active'           => true,
                'description'         => '5% discount with only 2 uses remaining (8/10 used)',
            ],

            // 6. Expired Percentage Discount
            [
                'code'                => 'EXPIRED30',
                'type'                => DiscountTypeEnum::PERCENTAGE->value,
                'value'               => 30,
                'user_id'             => null,
                'min_order_amount'    => 0,
                'max_discount_amount' => null,
                'usage_limit'         => null,
                'usage_per_user'      => 999,
                'used_count'          => 0,
                'starts_at'           => now()->subDays(60),
                'expires_at'          => now()->subDays(1),
                'is_active'           => true,
                'description'         => '30% discount that expired yesterday',
            ],

            // 7. Scheduled Future Percentage Discount
            [
                'code'                => 'FUTURE40',
                'type'                => DiscountTypeEnum::PERCENTAGE->value,
                'value'               => 40,
                'user_id'             => null,
                'min_order_amount'    => 0,
                'max_discount_amount' => null,
                'usage_limit'         => 50,
                'usage_per_user'      => 999,
                'used_count'          => 0,
                'starts_at'           => now()->addDays(7),
                'expires_at'          => now()->addDays(14),
                'is_active'           => true,
                'description'         => '40% discount scheduled to start in 7 days',
            ],

            // 8. Inactive Percentage Discount
            [
                'code'                => 'INACTIVE50',
                'type'                => DiscountTypeEnum::PERCENTAGE->value,
                'value'               => 50,
                'user_id'             => null,
                'min_order_amount'    => 0,
                'max_discount_amount' => null,
                'usage_limit'         => null,
                'usage_per_user'      => 999,
                'used_count'          => 0,
                'starts_at'           => now()->subDays(7),
                'expires_at'          => now()->addDays(30),
                'is_active'           => false,
                'description'         => '50% discount that is currently deactivated',
            ],

            // 9. User-Specific Percentage with Strict Per-User Limit
            [
                'code'                => 'USER1VIP',
                'type'                => DiscountTypeEnum::PERCENTAGE->value,
                'value'               => 35,
                'user_id'             => 1,
                'min_order_amount'    => 200000,
                'max_discount_amount' => 100000,
                'usage_limit'         => null,
                'usage_per_user'      => 1,
                'used_count'          => 0,
                'starts_at'           => now()->subDays(7),
                'expires_at'          => now()->addDays(90),
                'is_active'           => true,
                'description'         => 'VIP 35% discount for user #1 - One time use only',
            ],

            // ============================================
            // FIXED AMOUNT DISCOUNTS
            // ============================================

            // 10. Basic Global Fixed Amount Discount
            [
                'code'                => 'CASH50K',
                'type'                => DiscountTypeEnum::AMOUNT->value,
                'value'               => 50000,
                'user_id'             => null,
                'min_order_amount'    => 0,
                'max_discount_amount' => null,
                'usage_limit'         => null,
                'usage_per_user'      => 999,
                'used_count'          => 0,
                'starts_at'           => now()->subDays(7),
                'expires_at'          => now()->addDays(30),
                'is_active'           => true,
                'description'         => 'Global 50,000 fixed discount - No restrictions',
            ],

            // 11. User-Specific Fixed Amount (User ID = 1)
            [
                'code'                => 'USER1CASH',
                'type'                => DiscountTypeEnum::AMOUNT->value,
                'value'               => 150000,
                'user_id'             => 1,
                'min_order_amount'    => 300000,
                'max_discount_amount' => null,
                'usage_limit'         => 5,
                'usage_per_user'      => 3,
                'used_count'          => 0,
                'starts_at'           => now()->subDays(7),
                'expires_at'          => now()->addDays(30),
                'is_active'           => true,
                'description'         => 'User-specific 150,000 fixed discount for user #1',
            ],

            // 12. Fixed Amount with High Minimum Order
            [
                'code'                => 'BIGORDER100K',
                'type'                => DiscountTypeEnum::AMOUNT->value,
                'value'               => 100000,
                'user_id'             => null,
                'min_order_amount'    => 1000000,
                'max_discount_amount' => null,
                'usage_limit'         => 20,
                'usage_per_user'      => 2,
                'used_count'          => 0,
                'starts_at'           => now()->subDays(7),
                'expires_at'          => now()->addDays(60),
                'is_active'           => true,
                'description'         => '100,000 fixed discount for orders above 1,000,000',
            ],

            // 13. Fixed Amount with Exhausted Usage Limit
            [
                'code'                => 'SOLDOUT25K',
                'type'                => DiscountTypeEnum::AMOUNT->value,
                'value'               => 25000,
                'user_id'             => null,
                'min_order_amount'    => 0,
                'max_discount_amount' => null,
                'usage_limit'         => 100,
                'usage_per_user'      => 1,
                'used_count'          => 100,
                'starts_at'           => now()->subDays(30),
                'expires_at'          => now()->addDays(30),
                'is_active'           => true,
                'description'         => '25,000 fixed discount - Usage limit reached (100/100)',
            ],

            // 14. Expired Fixed Amount Discount
            [
                'code'                => 'OLDCASH200K',
                'type'                => DiscountTypeEnum::AMOUNT->value,
                'value'               => 200000,
                'user_id'             => null,
                'min_order_amount'    => 0,
                'max_discount_amount' => null,
                'usage_limit'         => null,
                'usage_per_user'      => 999,
                'used_count'          => 0,
                'starts_at'           => now()->subDays(90),
                'expires_at'          => now()->subDays(30),
                'is_active'           => true,
                'description'         => '200,000 fixed discount that expired 30 days ago',
            ],

            // 15. Scheduled Future Fixed Amount Discount
            [
                'code'                => 'COMINGSOON75K',
                'type'                => DiscountTypeEnum::AMOUNT->value,
                'value'               => 75000,
                'user_id'             => null,
                'min_order_amount'    => 200000,
                'max_discount_amount' => null,
                'usage_limit'         => 50,
                'usage_per_user'      => 999,
                'used_count'          => 0,
                'starts_at'           => now()->addDays(14),
                'expires_at'          => now()->addDays(21),
                'is_active'           => true,
                'description'         => '75,000 fixed discount scheduled to start in 14 days',
            ],

            // 16. Inactive Fixed Amount Discount
            [
                'code'                => 'PAUSED300K',
                'type'                => DiscountTypeEnum::AMOUNT->value,
                'value'               => 300000,
                'user_id'             => null,
                'min_order_amount'    => 500000,
                'max_discount_amount' => null,
                'usage_limit'         => null,
                'usage_per_user'      => 999,
                'used_count'          => 0,
                'starts_at'           => now()->subDays(7),
                'expires_at'          => now()->addDays(30),
                'is_active'           => false,
                'description'         => '300,000 fixed discount that is currently deactivated',
            ],

            // 17. User-Specific Fixed Amount for First Purchase
            [
                'code'                => 'USER1FIRST',
                'type'                => DiscountTypeEnum::AMOUNT->value,
                'value'               => 250000,
                'user_id'             => 1,
                'min_order_amount'    => 500000,
                'max_discount_amount' => null,
                'usage_limit'         => 1,
                'usage_per_user'      => 1,
                'used_count'          => 0,
                'starts_at'           => now()->subDays(1),
                'expires_at'          => now()->addDays(365),
                'is_active'           => true,
                'description'         => 'First purchase discount for user #1 - 250,000 off on orders above 500,000',
            ],

            // 18. Small Fixed Amount - Welcome Discount
            [
                'code'                => 'WELCOME10K',
                'type'                => DiscountTypeEnum::AMOUNT->value,
                'value'               => 10000,
                'user_id'             => null,
                'min_order_amount'    => 50000,
                'max_discount_amount' => null,
                'usage_limit'         => 500,
                'usage_per_user'      => 1,
                'used_count'          => 250,
                'starts_at'           => now()->subDays(30),
                'expires_at'          => now()->addDays(60),
                'is_active'           => true,
                'description'         => 'Welcome discount - 10,000 off first order (250/500 used)',
            ],
        ];

        foreach ($discounts as $discount) {
            try {
                StoreDiscountAction::run($discount);
            } catch (Throwable $e) {
                $this->command->error("Failed to create discount '{$discount['code']}': " . $e->getMessage());

                continue;
            }
        }

        $this->command->info('✅ Created ' . count($discounts) . ' discount codes successfully!');
        $this->command->newLine();
        $this->command->info('📊 Summary:');
        $this->command->info('   - Percentage Discounts: 9');
        $this->command->info('   - Fixed Amount Discounts: 9');
        $this->command->info('   - User-Specific (User #1): 4');
        $this->command->info('   - Global Discounts: 14');
        $this->command->info('   - Active & Valid: 11');
        $this->command->info('   - Expired: 2');
        $this->command->info('   - Scheduled: 2');
        $this->command->info('   - Inactive: 2');
        $this->command->info('   - Usage Limit Reached: 1');
    }
}
