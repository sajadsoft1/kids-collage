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
                'view_count'  => $row['view_count'] ?? 0,
                'languages'   => $row['languages'],
                'slug'        => $row['slug'] ?? \Illuminate\Support\Str::slug($row['title']),
            ]);

            try {
                if (isset($row['path'])) {
                    $model->addMedia($row['path'])
                        ->preservingOriginal()
                        ->toMediaCollection('image');
                }
            } catch (Exception) {
                // do nothing
            }
        }
    }
}
