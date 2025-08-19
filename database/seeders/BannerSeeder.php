<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Banner\StoreBannerAction;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['banner'] as $row) {
            $category = StoreBannerAction::run([
                'title'       => $row['title'],
                'description' => $row['description'],
                'published'   => $row['published'],
                'size'        => $row['size'],
            ]);
        }

        try {
            $category->addMedia($row['path'])
                ->preservingOriginal()
                ->toMediaCollection('image');
        } catch (Exception) {
            // do nothing
        }
    }
}
