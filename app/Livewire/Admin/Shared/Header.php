<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

use App\Helpers\Utils;
use App\Models\Branch;
use App\Models\User;
use App\View\Composers\NavbarComposer;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Mary\Traits\Toast;

class Header extends Component
{
    use Toast;

    public bool $notifications_drawer = false;

    public bool $profile_drawer = false;

    public string $nav_class = '';

    public string $container_size = 'container';

    #[Locked]
    public bool $showMenu = false;

    /** Mount the component. */
    public function mount(string $nav_class = '', bool $showMenu = false): void
    {
        $this->nav_class = $nav_class;
        $this->showMenu = $showMenu;
        $this->container_size = session('container_size', 'container');
    }

    /** Toggle container size between container and fluid. */
    public function toggleContainerSize(): void
    {
        $this->container_size = $this->container_size === 'container' ? 'fluid' : 'container';
        session(['container_size' => $this->container_size]);
        $this->dispatch('container-size-changed', size: $this->container_size);
        $this->js('window.location.reload()');
    }

    /** Switch to a different branch. */
    public function switchBranch(int $branchId): void
    {
        Utils::setCurrentBranchId($branchId);
        $this->js('window.location.reload()');
    }

    /** Get the navigation menu for the current user. */
    private function getNavbarMenu(): array
    {
        return app(NavbarComposer::class)->getMenu();
    }

    public function render(): View
    {
        $user = Auth::user();
        $branches = collect();
        $currentBranchId = Utils::getCurrentBranchId();

        if ($user instanceof User) {
            $branches = $user->branches()->active()->get();
        }

        return view('livewire.admin.shared.header', [
            'notificaations' => DatabaseNotification::query()
                ->where('notifiable_type', User::class)
                ->where('notifiable_id', Auth::id())
                ->whereNull('read_at')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(),
            'navbarMenu' => $this->showMenu ? $this->getNavbarMenu() : [],
            'branches' => $branches,
            'currentBranchId' => $currentBranchId,
            'currentBranch' => Branch::find($currentBranchId),
        ]);
    }
}
