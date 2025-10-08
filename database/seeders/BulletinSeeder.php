<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Bulletin\StoreBulletinAction;
use Illuminate\Database\Seeder;

class BulletinSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['bulletin'] as $row) {
            $model = StoreBulletinAction::run([
                'title'         => $row['title'],
                'description'   => $row['description'],
                'body'          => $row['body'] ?? $row['description'],
                'published'     => $row['published'],
                'published_at'  => $row['published_at'] ?? now(),
                'user_id'       => $row['user_id'] ?? 1,
                'category_id'   => $row['category_id'] ?? 1,
                'view_count'    => $row['view_count'] ?? 0,
                'comment_count' => $row['comment_count'] ?? 0,
                'wish_count'    => $row['wish_count'] ?? 0,
                'languages'     => $row['languages'],
                'slug'          => $row['slug'] ?? \Illuminate\Support\Str::slug($row['title']),
                'tags'          => $row['tags'] ?? ['test5','news','old-news'],
            ]);

            if (isset($row['path'])) {
                $model->addMedia($row['path'])
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            }
        }
    }
}
