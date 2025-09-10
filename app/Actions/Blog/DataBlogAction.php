<?php

declare(strict_types=1);

namespace App\Actions\Blog;

use Lorisleiva\Actions\Concerns\AsAction;

class DataBlogAction
{
    use AsAction;

    public function handle(array $payload = []): array
    {
        return [];
    }
}
