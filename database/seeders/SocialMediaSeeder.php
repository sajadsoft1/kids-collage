<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\SocialMedia\StoreSocialMediaAction;
use Exception;
use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['social_media'] as $row) {
            $model = StoreSocialMediaAction::run([
                'title' => $row['title'],
                'position' => $row['position'],
                'link' => $row['link'],
                'ordering' => $row['ordering'],
            ]);
        }

        try {
            $model->addMedia($row['path'])
                ->preservingOriginal()
                ->toMediaCollection('image');
        } catch (Exception) {
            // do nothing
        }
    }
}
