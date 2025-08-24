<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Client\StoreClientAction;
use Illuminate\Database\Seeder;
use Exception;


class ClientSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['client'] as $row) {
            $model = StoreClientAction::run([
                'title' => $row['title'],
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
