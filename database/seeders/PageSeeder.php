<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Page\StorePageAction;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['about_us'] as $row) {
            $page = StorePageAction::run([
                'slug' => $row['slug'],
                'title' => $row['title'],
                'body' => $row['body'],
                'type' => $row['type'],
                'deletable' => $row['deletable'],
            ]);
            $page->addMedia($row['path'])
                ->preservingOriginal()
                ->toMediaCollection('image');
        }
        foreach ($data['rules'] as $row) {
            $page = StorePageAction::run([
                'slug' => $row['slug'],
                'title' => $row['title'],
                'body' => $row['body'],
                'type' => $row['type'],
                'deletable' => $row['deletable'],
            ]);
            $page->addMedia($row['path'])
                ->preservingOriginal()
                ->toMediaCollection('image');
        }
    }
}
