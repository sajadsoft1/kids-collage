<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Faq\StoreFaqAction;
use App\Enums\CategoryTypeEnum;
use App\Models\Category;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');

        foreach ($data['faq'] as $row) {
            $model = StoreFaqAction::run([
                'title' => $row['title'],
                'description' => $row['description'],
                'published' => $row['published'],
                'category_id' => Category::where('type', CategoryTypeEnum::FAQ->value)->first()->id,
                'favorite' => $row['favorite'],
                'ordering' => $row['ordering'],
                'deletable' => false,
            ]);
        }
    }
}
