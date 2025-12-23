<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

use App\Models\User;
use App\Services\Menu\MenuService;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Sidebar extends Component
{
    #[Locked]
    public array $modules = [];

    #[Locked]
    public string $activeModuleKey = '';

    #[Locked]
    public string $defaultModule = '';

    #[Locked]
    public bool $isDirectLinkActive = false;

    #[Locked]
    public bool $initialSidebarOpen = false;

    #[Locked]
    public int $notificationCount = 0;

    #[Locked]
    public array $notifications = [];

    #[Locked]
    public string $currentBranch = 'شعبه مرکزی';

    public function mount(
        MenuService $menuService,
        NotificationService $notificationService
    ): void {
        $menuData = $menuService->getActiveModuleData();

        // Menu data is already an array, no need for json_decode/json_encode
        $this->modules = $menuData['modules'];
        $this->activeModuleKey = $menuData['activeModuleKey'] ?? '';
        $this->defaultModule = $menuData['defaultModule'];
        $this->isDirectLinkActive = $menuData['isDirectLinkActive'];
        $this->initialSidebarOpen = ! $this->isDirectLinkActive;

        // Get notifications using NotificationService
        $user = Auth::user();
        if ($user instanceof User) {
            $this->notificationCount = $notificationService->getUnreadCount($user);
            $this->notifications = $notificationService->getRecentNotifications($user);
        }

        // Load current branch from localStorage (symbolic for now)
        // This will be implemented in a later phase
    }

    public function render(): View
    {
        return view('livewire.admin.shared.sidebar', [
            'initialSidebarOpen' => $this->initialSidebarOpen,
            'activeModuleKey' => $this->activeModuleKey,
        ]);
    }
}
