<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\License\StoreLicenseAction;
use Exception;
use Illuminate\Database\Seeder;

class LicenseSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['license'] as $row) {
            $model = StoreLicenseAction::run([
                'title'       => $row['title'],
                'description' => $row['description'],
                'published'   => $row['published'],
                'languages'   => $row['languages'],
            ]);

            try {
                $model->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }
        }
    }
}
