<?php

declare(strict_types=1);

namespace App\Actions\Blog;

use App\Models\Blog;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteBlogAction
{
    use AsAction;

    /** @throws Throwable */
    public function handle(Blog $blog): bool
    {
        return DB::transaction(function () use ($blog) {
            return $blog->delete();
        });
    }
}
