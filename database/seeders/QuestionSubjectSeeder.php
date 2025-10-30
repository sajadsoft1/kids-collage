<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuestionSubjectSeeder extends Seeder
{
    public function run(): void
    {
        if (class_exists(\App\Models\QuestionSubject::class)) {
            \App\Models\QuestionSubject::query()->insert([
                ['languages' => json_encode(['fa' => ['title' => 'ریاضی', 'description' => null]]), 'created_at' => now(), 'updated_at' => now()],
                ['languages' => json_encode(['fa' => ['title' => 'علوم', 'description' => null]]), 'created_at' => now(), 'updated_at' => now()],
                ['languages' => json_encode(['fa' => ['title' => 'ادبیات', 'description' => null]]), 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }
}
