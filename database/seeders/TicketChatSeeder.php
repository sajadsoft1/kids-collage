<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Karnoweb\TicketChat\Models\Department;

class TicketChatSeeder extends Seeder
{
    public function run(): void
    {
        if (Department::query()->exists()) {
            return;
        }

        Department::query()->create([
            'name' => 'پشتیبانی عمومی',
            'slug' => 'general-support',
            'description' => 'دپارتمان پشتیبانی عمومی',
            'is_active' => true,
            'is_default' => true,
            'order' => 0,
            'auto_assign_strategy' => 'manual',
        ]);
    }
}
