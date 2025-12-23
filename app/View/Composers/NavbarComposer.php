<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Models\User;
use App\Services\Menu\Contracts\MenuProviderInterface;
use App\Services\Menu\MenuBuilderFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NavbarComposer implements MenuProviderInterface
{
    public function __construct(
        private readonly MenuBuilderFactory $menuBuilderFactory
    ) {}

    public function compose(View $view): void
    {
        $user = Auth::user();
        $view->with(
            'navbarMenu',
            $this->menuByUserType($user)
        );
    }

    /**
     * Get the menu for the current authenticated user.
     * Implements MenuProviderInterface
     *
     * @return array<int, array<string, mixed>>
     */
    public function getMenu(): array
    {
        $user = Auth::user();

        if ( ! $user) {
            return [];
        }

        return $this->menuByUserType($user);
    }

    private function menuByUserType(User $user): array
    {
        $builder = $this->menuBuilderFactory->createForUser($user);

        return $builder->build($user);
    }
}
