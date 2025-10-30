<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\CategoryTypeEnum;
use App\Models\Category;
use App\Models\QuestionSubject;
use Illuminate\Database\Seeder;

class QuestionSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('type', CategoryTypeEnum::QUESTION->value)->first();
        QuestionSubject::factory()->count(10)->create([
            'category_id' => $category->id,
        ]);
    }
}
