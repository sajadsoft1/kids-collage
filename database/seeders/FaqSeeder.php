<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Category\StoreCategoryAction;
use App\Actions\Faq\StoreFaqAction;
use App\Enums\CategoryTypeEnum;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        $category = StoreCategoryAction::run([
            'slug' => 'faq' . random_int(1, 500000),
            'title' => $data['categories'][0]['title'],
            'description' => $data['categories'][0]['description'],
            'body' => $data['categories'][0]['body'],
            'type' => CategoryTypeEnum::FAQ->value,
            'parent_id' => $data['categories'][0]['parent_id'],
            'published' => true,
            'ordering' => $data['categories'][0]['ordering'],
            'seo_title' => $data['categories'][0]['seo_options']['title'],
            'seo_description' => $data['categories'][0]['seo_options']['description'],
            'canonical' => $data['categories'][0]['seo_options']['canonical'],
            'old_url' => $data['categories'][0]['seo_options']['old_url'],
            'redirect_to' => $data['categories'][0]['seo_options']['redirect_to'],
            'robots_meta' => $data['categories'][0]['seo_options']['robots_meta'],
            'created_at' => $data['categories'][0]['created_at'],
        ]);
        foreach ($data['faq'] as $row) {
            $model = StoreFaqAction::run([
                'title' => $row['title'],
                'description' => $row['description'],
                'published' => $row['published'],
                'category_id' => $category->id,
                'favorite' => $row['favorite'],
                'ordering' => $row['ordering'],
                'deletable' => false,
            ]);
        }
    }
}
