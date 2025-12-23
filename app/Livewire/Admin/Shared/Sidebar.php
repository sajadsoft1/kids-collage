<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

use App\Models\User;
use App\Services\Menu\MenuService;
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

    public function mount(MenuService $menuService): void
    {
        $menuData = $menuService->getActiveModuleData();

        // Ensure all data is serializable by converting to array
        $this->modules = json_decode(json_encode($menuData['modules']), true);
        $this->activeModuleKey = $menuData['activeModuleKey'] ?? '';
        $this->defaultModule = $menuData['defaultModule'];
        $this->isDirectLinkActive = $menuData['isDirectLinkActive'];
        $this->initialSidebarOpen = ! $this->isDirectLinkActive;

        // Get notifications from database
        $user = auth()->user();
        if ($user instanceof User) {
            $this->notificationCount = $user->unreadNotifications()->count();
            $this->notifications = $user->notifications()
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($notification) {
                    $data = $notification->data ?? [];

                    return [
                        'id' => $notification->id,
                        'title' => $data['title'] ?? 'اعلان جدید',
                        'body' => $data['body'] ?? $data['subtitle'] ?? '',
                        'created_at' => $notification->created_at?->diffForHumans() ?? 'همین الان',
                        'read_at' => $notification->read_at,
                    ];
                })
                ->toArray();
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
