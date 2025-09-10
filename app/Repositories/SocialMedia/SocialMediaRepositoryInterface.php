<?php

declare(strict_types=1);

namespace App\Repositories\SocialMedia;

use App\Repositories\BaseRepositoryInterface;
use App\Models\SocialMedia;

interface SocialMediaRepositoryInterface extends BaseRepositoryInterface
{

    public function extra(array $payload = []): array;

}
