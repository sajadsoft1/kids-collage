<?php

namespace Database\Seeders;

use App\Actions\ContactUs\StoreContactUsAction;
use App\Actions\Page\StorePageAction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['about_us'] as $row) {
            $page=StorePageAction::run([
                'slug'            => $row['slug'],
                'title'           => $row['title'],
                'body'            => $row['body'],
                'type'            => $row['type'],
                'deletable'       => $row['deletable'],
                'seo_title'       => $row['seo_options']['title'],
                'seo_description' => $row['seo_options']['description'],
                'canonical'       => $row['seo_options']['canonical'],
                'old_url'         => $row['seo_options']['old_url'],
                'redirect_to'     => $row['seo_options']['redirect_to'],
                'robots_meta'     => $row['seo_options']['robots_meta'],
            ]);
            $page->addMedia($row['path'])
                ->preservingOriginal()
                ->toMediaCollection('image');
        }
    }
}
