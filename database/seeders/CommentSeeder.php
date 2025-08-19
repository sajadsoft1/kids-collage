<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\Comment\StoreCommentAction;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $data = require database_path('seeders/data/karno.php');
        foreach ($data['comment'] as $row) {
            StoreCommentAction::run([
                'published'      => $row['published'],
                'user_id'        => $row['user_id'],
                'morphable_id'   => $row['morphable_id'],
                'morphable_type' => $row['morphable_type'],
                'comment'        => $row['comment'],
            ]);
        }
    }
}
