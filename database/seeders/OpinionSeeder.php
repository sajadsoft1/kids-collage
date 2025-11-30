<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Opinion\StoreOpinionAction;
use Exception;
use Illuminate\Database\Seeder;

class OpinionSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['opinion'] as $row) {
            $model = StoreOpinionAction::run([
                'published' => $row['published'],
                'ordering' => $row['ordering'],
                'company' => $row['company'] ?? null,
                'user_name' => $row['user_name'],
                'comment' => $row['comment'],
            ]);

            try {
                $model->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }
            if (isset($row['video'])) {
                try {
                    $model->addMedia($row['video'])
                        ->preservingOriginal()
                        ->toMediaCollection('video');
                } catch (Exception) {
                    // do nothing
                }
            }
        }
    }
}
