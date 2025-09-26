<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Room\StoreRoomAction;
use Exception;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['room'] as $row) {
            $model = StoreRoomAction::run([
                'title'       => $row['title'],
                'description' => $row['description'],
                'capacity'    => $row['capacity'],
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
