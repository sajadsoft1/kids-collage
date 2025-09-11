<?php

declare(strict_types=1);

namespace App\Repositories\SocialMedia;

use App\Repositories\BaseRepositoryInterface;

interface SocialMediaRepositoryInterface extends BaseRepositoryInterface
{
    public function extra(array $payload = []): array;
}
