<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Event\StoreEventAction;
use App\Enums\CategoryTypeEnum;
use App\Models\Category;
use Exception;
use Illuminate\Database\Seeder;
use Random\RandomException;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws RandomException
     */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno_content.php');
        foreach ($data['events'] as $row) {
            $blog = StoreEventAction::run([
                'slug' => $row['slug'],
                'title' => $row['title'],
                'description' => $row['description'],
                'body' => $row['body'],
                'category_id' => Category::where('type', CategoryTypeEnum::EVENT->value)->first()->id,
                'published' => $row['published'],
                'start_date' => $row['start_date'],
                'end_date' => $row['end_date'],
                'location' => $row['location'],
                'capacity' => $row['capacity'],
                'price' => $row['price'],
                'is_online' => $row['is_online'],
                'published_at' => $row['published_at'],
                'tags' => $row['tags'] ?? ['event1', 'event2'],
            ]);

            try {
                $blog->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            } catch (Exception) {
                // do nothing
            }
        }
    }
}
