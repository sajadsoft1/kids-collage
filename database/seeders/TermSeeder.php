<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Term\StoreTermAction;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    public function run(): void
    {
        $data = require database_path('seeders/data/karno_lms.php');

        foreach ($data['term'] as $roomData) {
            StoreTermAction::run([
                'title'       => $roomData['title'],
                'description' => $roomData['description'],
                'start_date'  => $roomData['start_date'],
                'end_date'    => $roomData['end_date'],
                'status'      => $roomData['status'],
            ]);
        }
    }
}
