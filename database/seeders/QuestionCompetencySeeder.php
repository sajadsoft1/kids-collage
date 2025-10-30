<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuestionCompetencySeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\QuestionCompetency::query()->insert([
            ['languages' => json_encode(['fa' => ['title' => 'شایستگی ۱', 'description' => null]]), 'created_at' => now(), 'updated_at' => now()],
            ['languages' => json_encode(['fa' => ['title' => 'شایستگی ۲', 'description' => null]]), 'created_at' => now(), 'updated_at' => now()],
            ['languages' => json_encode(['fa' => ['title' => 'شایستگی ۳', 'description' => null]]), 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
