<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinancialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('💰 Seeding Financial System...');
        $this->call([
//            OrderSeeder::class,        // Customer orders and purchases
//            PaymentSeeder::class,      // Payment transactions and records
//            InstallmentSeeder::class,  // Installment plans and schedules
        ]);
        $this->command->info('✅ Financial System seeded successfully!');
    }
}
