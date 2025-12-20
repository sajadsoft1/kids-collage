<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

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

    public function mount(MenuService $menuService): void
    {
        $menuData = $menuService->getActiveModuleData();

        // Ensure all data is serializable by converting to array
        $this->modules = json_decode(json_encode($menuData['modules']), true);
        $this->activeModuleKey = $menuData['activeModuleKey'] ?? '';
        $this->defaultModule = $menuData['defaultModule'];
        $this->isDirectLinkActive = $menuData['isDirectLinkActive'];
        $this->initialSidebarOpen = ! $this->isDirectLinkActive;
    }

    public function render(): View
    {
        return view('livewire.admin.shared.sidebar', [
            'initialSidebarOpen' => $this->initialSidebarOpen,
        ]);
    }
}
