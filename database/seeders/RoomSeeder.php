<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        // Create some sample rooms
        $rooms = [
            [
                'name'      => 'Classroom A',
                'capacity'  => 30,
                'location'  => 'Building 1, Floor 1',
                'languages' => ['en', 'fa'],
            ],
            [
                'name'      => 'Classroom B',
                'capacity'  => 25,
                'location'  => 'Building 1, Floor 2',
                'languages' => ['en', 'fa'],
            ],
            [
                'name'      => 'Computer Lab',
                'capacity'  => 20,
                'location'  => 'Building 2, Floor 1',
                'languages' => ['en'],
            ],
            [
                'name'      => 'Meeting Room',
                'capacity'  => 15,
                'location'  => 'Building 1, Floor 3',
                'languages' => ['en', 'fa'],
            ],
        ];

        foreach ($rooms as $roomData) {
            Room::create($roomData);
        }
    }
}
