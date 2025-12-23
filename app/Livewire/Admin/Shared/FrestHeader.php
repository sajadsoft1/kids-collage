<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Shared;

use App\Helpers\NotifyHelper;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Mary\Traits\Toast;

class FrestHeader extends Component
{
    use Toast;

    public bool $notifications_drawer = false;

    public bool $messages_drawer = false;

    #[Locked]
    public bool $showMenu = false;

    public string $search = '';

    /** Mount the component. */
    public function mount(bool $showMenu = false): void
    {
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

    /** Get unread notifications with caching. */
    #[Computed]
    public function notifications(): Collection
    {
        $userId = Auth::id();

        if ( ! $userId) {
            return collect();
        }

        return Cache::remember(
            "user.{$userId}.unread_notifications",
            60, // 1 minute cache
            fn () => DatabaseNotification::query()
                ->where('notifiable_type', User::class)
                ->where('notifiable_id', $userId)
                ->whereNull('read_at')
                ->orderByDesc('created_at')
                ->limit(8)
                ->get()
        );
    }

    /** Get the navigation menu for the current user. */
    #[Computed]
    public function navbarMenu(): array
    {
        if ( ! $this->showMenu) {
            return [];
        }

        return app(MenuProviderInterface::class)->getMenu();
    }

    public function render(): View
    {
        return view('livewire.admin.shared.frest-header');
    }
}
