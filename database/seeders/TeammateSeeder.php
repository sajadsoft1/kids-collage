<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Teammate\StoreTeammateAction;
use Exception;
use Illuminate\Database\Seeder;

class TeammateSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['teammate'] as $row) {
            $model=StoreTeammateAction::run([
                'title' => $row['title'],
                'description' => $row['description'],
                'birthday' => $row['birthday'],
                'position' => $row['position'],
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
