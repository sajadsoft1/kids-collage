<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

use App\Helpers\NotifyHelper;
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

    public string $nav_class = '';

    #[Locked]
    public bool $showMenu = false;

    /** Mount the component. */
    public function mount(string $nav_class = '', bool $showMenu = false): void
    {
        $this->nav_class = $nav_class;
        $this->showMenu = $showMenu;
    }

    /** Show notification toast. */
    public function toastNotification(string $notificationId): void
    {
        $notification = DatabaseNotification::find($notificationId);
        if ($notification) {
            $this->info(NotifyHelper::subTitle($notification->data));
        }
    }

    /** Get the navigation menu for the current user. */
    private function getNavbarMenu(): array
    {
        return app(NavbarComposer::class)->getMenu();
    }

    public function render(): View
    {
        return view('livewire.admin.shared.header', [
            'notificaations' => DatabaseNotification::query()
                ->where('notifiable_type', User::class)
                ->where('notifiable_id', Auth::id())
                ->whereNull('read_at')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(),
            'navbarMenu' => $this->showMenu ? $this->getNavbarMenu() : [],
        ]);
    }
}
