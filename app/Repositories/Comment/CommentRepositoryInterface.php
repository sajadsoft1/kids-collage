<?php

declare(strict_types=1);

namespace App\Repositories\Comment;

use App\Repositories\BaseRepositoryInterface;
use App\Models\Comment;

interface CommentRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
