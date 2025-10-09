<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FinancialSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $this->command->info('💰 Seeding Financial System...');
        $this->call([
            DiscountSeeder::class,
            OrderSeeder::class,        // Customer orders and purchases
        ]);
        $this->command->info('✅ Financial System seeded successfully!');
    }
}
