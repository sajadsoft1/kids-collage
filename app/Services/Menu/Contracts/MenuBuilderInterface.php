<?php

declare(strict_types=1);

namespace App\Services\Menu\Contracts;

use App\Models\User;

interface MenuBuilderInterface
{
    /**
     * Build menu array for the given user
     *
     * @return array<int, array<string, mixed>>
     */
    public function build(User $user): array;
}
