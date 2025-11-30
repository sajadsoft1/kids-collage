<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Category\StoreCategoryAction;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno_content.php');

        foreach ($data['categories'] as $row) {
            StoreCategoryAction::run([
                'slug' => $row['slug'],
                'title' => $row['title'],
                'description' => $row['description'],
                'body' => $row['body'],
                'type' => $row['type'],
                'parent_id' => $row['parent_id'],
                'published' => true,
                'ordering' => $row['ordering'],
                'created_at' => $row['created_at'],
            ]);
        }
    }
}
