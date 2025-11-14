<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Category\StoreCategoryAction;
use App\Actions\Event\StoreEventAction;
use App\Enums\CategoryTypeEnum;
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
        $data    = require database_path('seeders/data/karno.php');
        $category=StoreCategoryAction::run([
            'title'       => 'events',
            'description' => 'A collection of exciting events happening around you.',
            'body'        => 'Discover a variety of events ranging from concerts and festivals to workshops and community gatherings. Stay updated and never miss out on the fun!',
            'slug'        => 'events',
            'published'   => true,
            'type'        => CategoryTypeEnum::EVENT->value,
        ]);
        foreach ($data['events'] as $row) {
            $blog = StoreEventAction::run([
                'slug'            => $row['slug'],
                'title'           => $row['title'],
                'description'     => $row['description'],
                'body'            => $row['body'],
                'category_id'     => $category->id,
                'published'       => $row['published'],
                'start_date'      => $row['start_date'],
                'end_date'        => $row['end_date'],
                'location'        => $row['location'],
                'capacity'        => $row['capacity'],
                'price'           => $row['price'],
                'is_online'       => $row['is_online'],
                'published_at'    => $row['published_at'],
                'seo_title'       => $row['seo_options']['title'],
                'seo_description' => $row['seo_options']['description'],
                'canonical'       => $row['seo_options']['canonical'],
                'old_url'         => $row['seo_options']['old_url'],
                'redirect_to'     => $row['seo_options']['redirect_to'],
                'robots_meta'     => $row['seo_options']['robots_meta'],
                'tags'            => $row['tags'] ?? ['event1', 'event2'],
            ]);

            $blog->addMedia($row['path'])
                ->preservingOriginal()
                ->toMediaCollection('image');
        }
    }
}
