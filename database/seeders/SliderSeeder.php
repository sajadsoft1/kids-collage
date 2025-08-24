<?php

namespace Database\Seeders;

use App\Actions\Slider\StoreSliderAction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Exception;


class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['slider'] as $row) {
            $model = StoreSliderAction::run([
                'title'    => $row['title'],
                'description' => $row['position'],
                'published'     => $row['published'],
                'ordering' => $row['ordering'],
                'link' => $row['link'],
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
