<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\QuestionCompetency;
use Illuminate\Database\Seeder;

class QuestionCompetencySeeder extends Seeder
{
    public function run(): void
    {
        QuestionCompetency::factory()->count(10)->create();
    }
}
