<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Category\StoreCategoryAction;
use Exception;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');

        foreach ($data['categories'] as $row) {
            $category = StoreCategoryAction::run([
                'slug'            => $row['slug'],
                'title'           => $row['title'],
                'description'     => $row['description'],
                'body'            => $row['body'],
                'type'            => $row['type'],
                'parent_id'       => $row['parent_id'],
                'published'       => true,
                'ordering'        => $row['ordering'],
                'seo_title'       => $row['seo_options']['title'],
                'seo_description' => $row['seo_options']['description'],
                'canonical'       => $row['seo_options']['canonical'],
                'old_url'         => $row['seo_options']['old_url'],
                'redirect_to'     => $row['seo_options']['redirect_to'],
                'robots_meta'     => $row['seo_options']['robots_meta'],
                'created_at'      => $row['created_at'],
            ]);

            // Add images to the categories

            try {
                $category->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }
        }
    }
}
