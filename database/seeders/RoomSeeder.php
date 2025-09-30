<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Room\StoreRoomAction;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        // Create some sample rooms
        $data = require database_path('seeders/data/karno_lms.php');

        foreach ($data['room'] as $roomData) {
            StoreRoomAction::run([
                'name'     => $roomData['name'],
                'location' => $roomData['location'],
                'capacity' => $roomData['capacity'],
            ]);
        }
    }
}
